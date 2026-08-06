<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountHead;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PurchaseController;

class PurchasePOSController extends Controller
{
    public function index()
    {
        $products = Product::where("is_active", true)->with("warehouseStocks")->get();

        $posProducts = [];
        foreach ($products as $p) {
            $ppb = $p->pieces_per_box > 0 ? $p->pieces_per_box : 1;

            $variants = [];
            if ($p->color) {
                try {
                    $parsed = is_string($p->color) ? json_decode($p->color, true) : $p->color;
                    if (is_array($parsed) && count($parsed) > 0 && isset($parsed[0]['name'])) {
                        $variants = $parsed;
                    }
                } catch (\Exception $e) {}
            }

            if (count($variants) > 0) {
                // Fetch all sales, returns, purchases, and purchase returns for this product to distribute
                $salesList = DB::table('sale_items')
                    ->where('product_id', $p->id)
                    ->select('total_pieces', 'color')
                    ->get();

                $returnsList = DB::table('sale_return_items as sri')
                    ->join('sale_returns as sr', 'sr.id', '=', 'sri.sale_return_id')
                    ->where('sri.product_id', $p->id)
                    ->select('sri.qty', 'sri.color', 'sr.sale_id')
                    ->get();

                $saleIds = $returnsList->pluck('sale_id')->unique()->toArray();
                $saleItemsMap = [];
                if (!empty($saleIds)) {
                    $siList = DB::table('sale_items')
                        ->whereIn('sale_id', $saleIds)
                        ->where('product_id', $p->id)
                        ->select('sale_id', 'color')
                        ->get();
                    foreach ($siList as $si) {
                        $saleItemsMap[$si->sale_id][] = $si->color;
                    }
                }

                $purchasesList = DB::table('purchase_items as pi')
                    ->join('purchases as pur', 'pur.id', '=', 'pi.purchase_id')
                    ->where('pi.product_id', $p->id)
                    ->whereIn('pur.status_purchase', ['approved', 'Returned', 'Partial'])
                    ->select('pi.qty as total_pieces', 'pi.color')
                    ->get();

                $purchaseReturnsList = DB::table('purchase_return_items as pri')
                    ->where('pri.product_id', $p->id)
                    ->select('pri.qty', 'pri.color')
                    ->get();

                $variantItems = [];
                $totalStockPieces = 0;

                foreach ($variants as $v) {
                    $size = (isset($v['size']) && $v['size'] !== '-') ? " {$v['size']}" : '';
                    $color = (isset($v['color']) && $v['color'] !== '-') ? " ({$v['color']})" : '';
                    $vName = ($v['name'] ?? $p->item_name) . $size . $color;
                    
                    $initial = (float) ($v['stock'] ?? 0);

                    // Calculate Purchased variant qty
                    $purchased = 0;
                    foreach ($purchasesList as $pItem) {
                        if ($this->matchSaleItemToVariant($pItem, $v)) {
                            $purchased += (float) $pItem->total_pieces;
                        }
                    }

                    // Calculate Purchase Returned variant qty
                    $pReturned = 0;
                    foreach ($purchaseReturnsList as $prItem) {
                        if ($this->matchSaleItemToVariant($prItem, $v)) {
                            $pReturned += (float) $prItem->qty;
                        }
                    }
                    
                    // Calculate Sold variant qty
                    $sold = 0;
                    foreach ($salesList as $sItem) {
                        if ($this->matchSaleItemToVariant($sItem, $v)) {
                            $sold += (float) $sItem->total_pieces;
                        }
                    }

                    // Calculate Returned variant qty
                    $returnedQty = 0;
                    foreach ($returnsList as $rItem) {
                        $rColor = $rItem->color;
                        if (empty($rColor)) {
                            $saleColors = $saleItemsMap[$rItem->sale_id] ?? [];
                            $rColor = !empty($saleColors) ? $saleColors[0] : '';
                        }
                        $rItemCopy = (object)[
                            'qty' => $rItem->qty,
                            'color' => $rColor
                        ];
                        if ($this->matchSaleItemToVariant($rItemCopy, $v)) {
                            $returnedQty += (float) $rItem->qty;
                        }
                    }

                    $vBalance = max(0, $initial + $purchased - $sold + $returnedQty - $pReturned);
                    $totalStockPieces += $vBalance;

                    $vStockDisplay = $vBalance;
                    if (($p->size_mode === 'by_cartons' || $p->size_mode === 'by_size') && $ppb > 1) {
                        $vBoxes = floor($vBalance / $ppb);
                        $vLoose = $vBalance % $ppb;
                        $vStockDisplay = $vLoose > 0 ? "$vBoxes.$vLoose" : $vBoxes;
                    }

                    $v['current_stock'] = $vStockDisplay;
                    $variantJson = json_encode($v);

                    $variantItems[] = [
                        'id' => $p->id . '|variant|' . base64_encode($variantJson),
                        'name' => $vName,
                        'size_val' => $v['size'] ?? '-',
                        'color_val' => $v['color'] ?? '-',
                        'price' => $v['purch_price'] ?? $p->purchase_price_per_piece ?? 0,
                        'wholesale_price' => $v['wholesale_price'] ?? $p->wholesale_price ?? 0,
                        'weight_per_piece' => $v['weight_per_piece'] ?? $p->weight_per_piece ?? 0,
                        'stock_pieces' => $vBalance,
                        'stock' => $vStockDisplay,
                        'variant_data' => base64_encode($variantJson)
                    ];
                }

                $totalStockDisplay = $totalStockPieces;
                if (($p->size_mode === 'by_cartons' || $p->size_mode === 'by_size') && $ppb > 1) {
                    $boxes = floor($totalStockPieces / $ppb);
                    $loose = $totalStockPieces % $ppb;
                    $totalStockDisplay = $loose > 0 ? "$boxes.$loose" : $boxes;
                }

                $posProducts[] = [
                    'id' => $p->id,
                    'name' => $p->item_name,
                    'sku' => $p->item_code ?? '',
                    'stock' => $totalStockDisplay,
                    'stock_pieces' => $totalStockPieces,
                    'size_mode' => $p->size_mode,
                    'pieces_per_box' => $ppb,
                    'price' => $p->purchase_price_per_piece ?? 0,
                    'wholesale_price' => $p->wholesale_price ?? 0,
                    'weight_per_piece' => $p->weight_per_piece ?? 0,
                    'image' => $p->image ? asset('uploads/products/'.$p->image) : null,
                    'variants' => $variantItems,
                ];

            } else {
                $stockPieces = (float) ($p->warehouseStocks->sum('total_pieces') ?? 0);
                $stockDisplay = $stockPieces;
                if (($p->size_mode === 'by_cartons' || $p->size_mode === 'by_size') && $ppb > 1) {
                    $boxes = floor($stockPieces / $ppb);
                    $loose = $stockPieces % $ppb;
                    $stockDisplay = $loose > 0 ? "$boxes.$loose" : $boxes;
                }

                $posProducts[] = [
                    'id' => $p->id,
                    'name' => $p->item_name,
                    'sku' => $p->item_code ?? '',
                    'stock' => $stockDisplay,
                    'stock_pieces' => $stockPieces,
                    'size_mode' => $p->size_mode,
                    'pieces_per_box' => $ppb,
                    'price' => $p->purchase_price_per_piece ?? 0,
                    'wholesale_price' => $p->wholesale_price ?? 0,
                    'weight_per_piece' => $p->weight_per_piece ?? 0,
                    'image' => $p->image ? asset('uploads/products/'.$p->image) : null,
                    'variants' => [],
                ];
            }
        }

        // Clean customers

        $cashAndBankHeads = AccountHead::whereIn("name", ["Cash", "Bank"])->pluck("id");
        $accounts = Account::whereIn("head_id", $cashAndBankHeads)->where("status", 1)->orderBy("title")->get();

        $balanceService = app(\App\Services\BalanceService::class);
        $apId = $balanceService->getAccountsPayableId();
        $vendorBalances = \App\Models\JournalEntry::where('party_type', Vendor::class)
            ->where('account_id', $apId)
            ->selectRaw('party_id, COALESCE(SUM(credit) - SUM(debit), 0) as balance')
            ->groupBy('party_id')
            ->pluck('balance', 'party_id');

        $vendors = Vendor::orderBy("name")->get()->map(function($vendor) use ($vendorBalances) {
            $vendor->balance = (float) ($vendorBalances[$vendor->id] ?? 0);
            return $vendor;
        });

        $customers = collect();

        return view("admin_panel.purchase_pos.index", compact("posProducts", "customers", "accounts", "vendors"));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        $data["vendor_id"] = $request->input("Vendor") ?? $request->input("customer_id");
        $data["payment_amount"] = $request->input("payment_amount");
        $data["payment_account_id"] = $request->input("payment_account_id");
        $data["action"] = "post";
        
        // Map POS cart fields to Purchase fields
        $data["price"] = $request->input("price_per_piece");
        // POS sends warehouse_id as array, Purchase expects single integer
        if (isset($data["warehouse_id"]) && is_array($data["warehouse_id"])) {
            $data["warehouse_id"] = $data["warehouse_id"][0] ?? 1;
        } else {
            $data["warehouse_id"] = 1; // Default fallback
        }

        // Add missing required fields for PurchaseController validation
        $productIds = (array) $request->input("product_id", []);
        $data["unit"] = array_fill(0, count($productIds), "pieces");
        
        // POS sends item_disc as absolute, PurchaseController expects item_discount as percentage
        $itemDiscs = (array) $request->input("item_disc", []);
        $prices = (array) $data["price"];
        $qtys = (array) $request->input("qty", []);
        $itemDiscountPercentages = [];
        
        foreach ($productIds as $i => $pid) {
            $absDisc = (float) ($itemDiscs[$i] ?? 0);
            $gross = ((float) ($qtys[$i] ?? 0)) * ((float) ($prices[$i] ?? 0));
            $pct = $gross > 0 ? ($absDisc / $gross) * 100 : 0;
            $itemDiscountPercentages[] = $pct;
        }
        $data["item_discount"] = $itemDiscountPercentages;
        $data["loose_qty"] = $qtys;

        $newRequest = new Request($data);
        $newRequest->headers->set("X-Requested-With", "XMLHttpRequest");

        $purchaseController = app(PurchaseController::class);
        $response = $purchaseController->store($newRequest);

        $responseData = $response->getData(true);
        if (isset($responseData["success"]) && $responseData["success"]) {
            return response()->json([
                "ok" => true,
                "message" => "Purchase Processed Successfully!",
                "invoice_url" => $responseData["invoice_url"] ?? null,
            ]);
        }

        $errorMsg = $responseData["message"] ?? "Failed to process purchase.";
        if (isset($responseData["errors"])) {
            $errorMsg .= " | Details: " . json_encode($responseData["errors"]);
        }

        return response()->json([
            "ok" => false,
            "message" => $errorMsg,
        ]);
    }

    private function matchSaleItemToVariant($saleItem, $variant)
    {
        $itemColor = $saleItem->color;
        if (empty($itemColor)) {
            return false;
        }

        $itemVariant = [];
        $b64Decoded = base64_decode($itemColor, true);
        if ($b64Decoded !== false) {
            $json = json_decode($b64Decoded, true);
            if (is_array($json)) {
                $itemVariant = $json;
            }
        }
        if (empty($itemVariant)) {
            $json = json_decode($itemColor, true);
            if (is_array($json)) {
                $itemVariant = $json;
            }
        }

        if (empty($itemVariant)) {
            return strtolower(trim($itemColor)) === strtolower(trim($variant['color'] ?? ''));
        }

        $vColor = strtolower(trim($variant['color'] ?? '-'));
        $vSize = strtolower(trim($variant['size'] ?? '-'));

        $itemVColor = strtolower(trim($itemVariant['color'] ?? ($itemVariant['color_val'] ?? '-')));
        $itemVSize = strtolower(trim($itemVariant['size'] ?? ($itemVariant['size_val'] ?? '-')));

        if ($vColor === '') $vColor = '-';
        if ($vSize === '') $vSize = '-';
        if ($itemVColor === '') $itemVColor = '-';
        if ($itemVSize === '') $itemVSize = '-';

        return $vColor === $itemVColor && $vSize === $itemVSize;
    }
}
