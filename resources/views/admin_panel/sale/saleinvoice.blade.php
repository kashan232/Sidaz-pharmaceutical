<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->id }}</title>
    <!-- Use Bootstrap for grid and utilities -->
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">


    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --border-color: #bdc3c7;
            --text-color: #2c3e50;
        }

        body {
            background-color: #f8f9fa;
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            /* Reduced base font size */
        }

        .invoice-container {
            max-width: 210mm;
            margin: 10px auto;
            background: #fff;
            padding: 20px;
            /* Reduced padding */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-height: 297mm;
            position: relative;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 22px;
            /* Reduced */
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 2px;
        }

        .invoice-title {
            text-align: center;
            font-size: 18px;
            /* Reduced */
            font-weight: bold;
            text-transform: uppercase;
            color: var(--accent-color);
            margin: 15px 0 10px 0;
            letter-spacing: 2px;
        }

        .info-box {
            border: 1px solid var(--border-color);
            padding: 8px;
            /* Reduced padding */
            height: 100%;
            border-radius: 4px;
            background-color: #fff;
        }

        .info-box-header {
            font-weight: bold;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 4px;
            padding-bottom: 2px;
            color: var(--primary-color);
            font-size: 11px;
            text-transform: uppercase;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            min-width: 70px;
            display: inline-block;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .invoice-table th {
            background-color: var(--primary-color);
            color: #fff;
            text-transform: uppercase;
            font-size: 11px;
            padding: 6px 4px;
            /* Reduced padding */
            border: 1px solid var(--primary-color);
        }

        .invoice-table td {
            border: 1px solid var(--border-color);
            padding: 4px 6px;
            /* Reduced padding */
            vertical-align: middle;
            font-size: 12px;
        }

        .invoice-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer-section {
            margin-top: 20px;
            border-top: 2px solid var(--primary-color);
            padding-top: 10px;
        }

        .terms-box {
            font-size: 11px;
            color: #666;
        }

        .terms-box ul {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .terms-box li {
            margin-bottom: 2px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .totals-table td {
            padding: 4px 8px;
            /* Reduced padding */
            border-bottom: 1px solid #eee;
        }

        .totals-table .total-row td {
            border-top: 2px solid var(--primary-color);
            font-weight: bold;
            font-size: 14px;
            color: var(--primary-color);
        }

        .signature-area {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 180px;
            text-align: center;
            padding-top: 5px;
        }

        .print-btn-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        @media print {
            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 10px;
                box-shadow: none;
                border: none;
                min-height: auto;
            }

            .print-btn-container {
                display: none;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 5mm;
            }
        }
    </style>
</head>

<body>

    <!-- Print Button -->
    <div class="print-btn-container">
        <button onclick="window.print()" class="btn btn-primary btn-sm shadow">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-printer-fill me-2" viewBox="0 0 16 16">
                <path
                    d="M0 9a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V9zm4-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2H4V3z" />
                <path d="M2.5 14.5A1.5 1.5 0 0 1 1 13V9a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v4a1.5 1.5 0 0 1-1.5 1.5h-13z" />
            </svg>
            Print
        </button>
        <a href="{{ route('sale.index') }}" class="btn btn-secondary btn-sm shadow ms-2">Back</a>
    </div>

    <div class="invoice-container">
        <!-- Company Header -->
        @if(!($isEstimate ?? false))
        <div class="company-info">
            <div class="company-name">{{ \App\Models\Setting::get('company_name', 'prowave technogies') }}</div>
            <div style="font-size: 12px;">{{ \App\Models\Setting::get('company_address', 'Hyderabad') }}</div>
             <p>{{ \App\Models\Setting::get('company_phone', '0327-9226901') }}</p>
        </div>
        @endif

        <div class="invoice-title">{{ ($isEstimate ?? false) ? 'Estimate' : 'Sales Invoice' }}</div>

        <!-- Info Grid -->
        @if(!($isEstimate ?? false))
        <div class="row g-2 mb-2">
            <!-- Left Box: Customer Info -->
            <div class="col-6">
                <div class="info-box">
                    <div class="info-box-header">Customer</div>
                    @if($sale->customer_relation?->customer_id)
                    <div style="font-size: 11px; color: #555;">
                        Code: <strong>{{ $sale->customer_relation->customer_id }}</strong>
                    </div>
                    @endif
                    <div><span class="info-label">Name:</span> <strong>{{ $sale->walkin_name ?? ($sale->customer_relation->customer_name ?? 'Walking Customer') }}</strong></div>
                    <div><span class="info-label">Address:</span> <span style="font-size:11px;">{{ $sale->customer_relation->address ?? '—' }}</span></div>
                    <div><span class="info-label">Mob:</span> <span style="font-size:11px;">{{ $sale->customer_relation->mobile ?? '—' }}</span></div>
                </div>
            </div>



            <!-- Right Box: Invoice Specifics -->
            <div class="col-6">
                <div class="info-box">
                    <div class="info-box-header">Reference</div>
                    <div><span class="info-label">Inv #:</span> <strong>{{ $sale->invoice_no }}</strong></div>
                    <div><span class="info-label">Date:</span> {{ $sale->created_at->format('d/m/Y') }}</div>
                    @if($sale->reference)
                    <div style="margin-top:4px; padding-top:4px; border-top:1px dashed #ddd;">
                        <span class="info-label">Remarks:</span>
                        <span style="font-size:11px; color:#333;">{{ $sale->reference }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="row g-2 mb-2">
             <div class="col-12 text-end">
                <div class="info-box">
                    <div><span class="info-label">Date:</span> {{ $sale->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Remarks -->
        @if ($sale->return_note)
            <div class="row mb-2">
                <div class="col-12">
                    <div class="info-box"
                        style="min-height: auto; padding: 4px 8px; background-color: #f1f5f9; font-style: italic;">
                        <strong>Note:</strong> {{ $sale->return_note }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th class="text-start" style="width: 38%">Description</th>
                    <th class="text-center" style="width: 14%">Shipped</th>
                    <th class="text-center" style="width: 10%">UOM</th>
                    <th class="text-end" style="width: 10%">Price</th>
                    <th class="text-end" style="width: 10%">Disc</th>
                    <th class="text-end" style="width: 13%">Net Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($saleItems as $item)
                    @php
                        // Get dimensions from database
                        $height = $item['height'] ?? 0;
                        $width = $item['width'] ?? 0;

                        // Calculate m² per piece and per box
                        $m2PerPiece = $height > 0 && $width > 0 ? ($height * $width) / 10000 : 0;
                        $piecesPerBox = (int)($item['pieces_per_box'] ?? 1);
                        if ($piecesPerBox <= 0) $piecesPerBox = 1;
                        $m2PerBox = $m2PerPiece * $piecesPerBox;

                        // Calculate boxes and loose pieces
                        $totalPieces = (int) $item['total_pieces'];
                        $boxes = floor($totalPieces / $piecesPerBox);
                        $loosePieces = $totalPieces % $piecesPerBox;

                        // Total M2 for line
                        $totalM2Line = $m2PerPiece * $totalPieces;
                        $sizeMode = $item['size_mode'] ?? 'by_size';
                    @endphp
                    <tr>
                        <td class="text-start">
                            <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">
                                {{ $item['item_name'] }}
                                @if (!empty($item['item_code']))
                                    <span class="text-muted fw-normal ms-1"
                                        style="font-size: 11px;">({{ $item['item_code'] }})</span>
                                @endif
                            </div>

                            <div style="font-size: 11px; color: #555; line-height: 1.2;">
                                @if (!empty($item['color']))
                                    <span class="badge bg-light text-dark border p-1"
                                        style="font-size: 9px; line-height:1;">
                                        @foreach ($item['color'] as $clr)
                                            {{ $clr }}
                                        @endforeach
                                    </span>
                                @endif

                                @if ($sizeMode == 'by_size')
                                    <span class='d-inline-block ms-1'>
                                        @if ($height > 0 && $width > 0)
                                            Dims: {{ number_format($width, 0) }}x{{ number_format($height, 0) }}
                                        @endif
                                    </span>
                                @endif

                                @if ($piecesPerBox > 1)
                                <span class="d-inline-block ms-1">
                                    Pack: {{ $piecesPerBox }} pcs
                                </span>
                                @endif
                            </div>
                        </td>

                        <td class="text-center" style="vertical-align: middle;">
                            @php
                                $variantUnit = strtolower($item['variant_unit'] ?? (is_array($item['color'] ?? null) ? ($item['color']['unit'] ?? '') : ''));
                                $weightGrams = (float)($item['weight_per_piece'] ?? (is_array($item['color'] ?? null) ? ($item['color']['weight_per_piece'] ?? 0) : 0));
                            @endphp

                            @if ($variantUnit === 'pcs' || $variantUnit === 'piece' || $variantUnit === 'pieces')
                                <div style="font-weight: bold; color: #2c3e50;">
                                    {{ $totalPieces }} Pcs
                                    @if ($weightGrams > 0)
                                        <small class="d-block text-muted" style="font-size: 10px;">({{ $weightGrams == (int)$weightGrams ? (int)$weightGrams : $weightGrams }}g)</small>
                                    @endif
                                </div>
                            @elseif (in_array($sizeMode, ['by_kg', 'by_gm', 'by_feet', 'by_meter']))
                                @php
                                    $uomLabel = match($sizeMode) {
                                        'by_kg' => 'Kg',
                                        'by_gm' => 'Gm',
                                        'by_feet' => 'Ft',
                                        'by_meter' => 'Meter',
                                        default => '',
                                    };
                                    $qtyVal = (float)($item['qty_box'] ?? $item['qty'] ?? $totalPieces);
                                    $displayQty = ($qtyVal == (int)$qtyVal) ? (int)$qtyVal : number_format($qtyVal, 3);
                                @endphp
                                <div style="font-weight: bold; color: #2c3e50;">
                                    {{ $displayQty }} {{ $uomLabel }}
                                </div>
                            @else
                                <div style="font-weight: bold; color: #2c3e50;">
                                    @if ($sizeMode == 'by_pieces')
                                        {{ $totalPieces }} Pcs
                                    @else
                                        @if ($boxes > 0 && $loosePieces > 0)
                                            {{ $boxes }} {{ $sizeMode == 'by_cartons' ? 'Carton' : 'Box' }} +
                                            {{ $loosePieces }} Pc
                                        @elseif ($boxes > 0)
                                            {{ $boxes }} {{ $sizeMode == 'by_cartons' ? 'Carton' : 'Box' }}
                                        @else
                                            {{ $loosePieces }} Pcs
                                        @endif
                                    @endif
                                </div>
                                <small class="text-muted" style="font-size: 10px;">({{ $totalPieces }} pcs)</small>
                            @endif
                        </td>

                        <td class="text-center" style="vertical-align: middle;">

                            @if ($sizeMode == 'by_pieces')
                            <span class="fw-bold">
                            Pieces
                        </span>
                            @elseif ($sizeMode == 'by_cartons')
                            <span class="fw-bold">
                            Cartons
                        </span>
                            @elseif ($sizeMode == 'by_size')
                            <span class="fw-bold">

                                {{ number_format($totalM2Line, 4) }}
                            </span> m²
                                @endif
                        </td>

                        <td class="text-end" style="vertical-align: middle;">
                            {{ number_format($item['price'], 2) }}
                        </td>

                        {{-- DISCOUNT COLUMN --}}
                        <td class="text-end" style="vertical-align: middle;">
                            @php
                                $discAmt  = (float)($item['discount_amount'] ?? 0);
                                $discPct  = (float)($item['discount_percent'] ?? 0);
                            @endphp
                            @if ($discAmt > 0)
                                <span class="text-danger">{{ number_format($discAmt, 2) }}</span>
                                @if ($discPct > 0)
                                    <br><small class="text-muted">({{ number_format($discPct, 1) }}%)</small>
                                @else
                                    <br><small class="text-muted">PKR</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-end fw-bold" style="vertical-align: middle;">
                            {{ number_format($item['total'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $exchangeReturn = \App\Models\SaleReturn::with('items.product')->where('remarks', 'LIKE', '%Invoice #'.$sale->invoice_no.'%')->first();
            $exchangeReturnedAmount = 0;
            if ($exchangeReturn) {
                $exchangeReturnedAmount = $exchangeReturn->items->sum('line_total');
            }
        @endphp

        @if($exchangeReturn && $exchangeReturn->items->count() > 0)
        <div class="mt-3">
            <h6 class="fw-bold mb-2">Returned Items (Exchange)</h6>
            <table class="items-table">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start" style="width: 5%">S.</th>
                        <th class="text-start" style="width: 38%">Description</th>
                        <th class="text-center" style="width: 14%">Qty</th>
                        <th class="text-center" style="width: 10%">UOM</th>
                        <th class="text-end" style="width: 10%">Price</th>
                        <th class="text-end" style="width: 10%">Disc</th>
                        <th class="text-end" style="width: 13%">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exchangeReturn->items as $retItem)
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">
                                <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">
                                    {{ $retItem->product->item_name ?? 'Unknown' }}
                                </div>
                                @php
                                    $retColorStr = '';
                                    if (!empty($retItem->color)) {
                                        $decoded = base64_decode($retItem->color, true);
                                        if ($decoded !== false && is_string($decoded) && str_starts_with(trim($decoded), '{')) {
                                            $parsed = json_decode($decoded, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                                                $parts = [];
                                                if (!empty($parsed['size']) && $parsed['size'] !== '-') $parts[] = $parsed['size'];
                                                if (!empty($parsed['color']) && $parsed['color'] !== '-') $parts[] = $parsed['color'];
                                                $retColorStr = implode(' | ', $parts);
                                            } else {
                                                $retColorStr = $retItem->color;
                                            }
                                        } else {
                                            $retColorStr = $retItem->color;
                                        }
                                    }
                                @endphp
                                @if($retColorStr)
                                    <div style="font-size: 11px; color: #555;">
                                        <span class="badge bg-light text-dark border p-1" style="font-size: 9px;">{{ $retColorStr }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ (float)$retItem->qty }} Pcs</td>
                            <td class="text-center fw-bold">Pieces</td>
                            <td class="text-end">{{ number_format($retItem->price, 2) }}</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end fw-bold">-{{ number_format($retItem->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="row mt-2">
            <div class="col-7">
                <div class="terms-box pt-2">
                    <p class="fw-bold mb-1">Terms & Conditions:</p>
                    <ul style="font-size: 10px;">
                        @php
                            $invoiceTerms = \App\Models\Setting::get('invoice_terms', "10% will be deducted on return of purchase goods within 7 days.\nLoose & Water Soak products will not be RETURNED.\nPlease bring this invoice for any returns or exchanges.");
                            $termLines = explode("\n", $invoiceTerms);
                        @endphp
                        @foreach($termLines as $line)
                            @if(trim($line))
                                <li>{{ trim($line) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4 pt-2">
                    <div class="signature-area">
                        Authorized Signature
                    </div>
                    <div class="small text-muted mt-1" style="font-size: 10px;">
                        Printed on: {{ date('d/m/Y h:i A') }}
                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="info-box" style="border: none; padding: 0;">
                    <table class="totals-table">
                        @php
                            $grossTotal  = collect($saleItems)->sum('total');
                            $totalDisc   = collect($saleItems)->sum('discount_amount');
                            $netBill     = $sale->total_net;          // after extra discount
                            $paidAmount  = (float)($sale->cash ?? 0);
                            $finalBal    = $previousBalance + $netBill - $paidAmount;
                        @endphp

                        @if ($totalDisc > 0)
                        <tr>
                            <td class="text-muted">Gross Total</td>
                            <td class="text-end text-muted">{{ number_format($grossTotal + $totalDisc, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Discount</td>
                            <td class="text-end text-danger">- {{ number_format($totalDisc, 2) }}</td>
                        </tr>
                        @endif

                        @if ($exchangeReturnedAmount > 0)
                        <tr>
                            <td class="text-muted">Return Value</td>
                            <td class="text-end text-danger">- {{ number_format($exchangeReturnedAmount, 2) }}</td>
                        </tr>
                        @endif

                        @php
                            $finalPayable = $netBill - $exchangeReturnedAmount;
                        @endphp
                        <tr>
                            <td class="fw-bold text-dark fs-6" style="border-top: 2px solid #34495e; padding-top: 8px;">
                                {{ $finalPayable < 0 ? 'Refund To Customer' : 'Net Payable' }}
                            </td>
                            <td class="text-end fw-bold text-dark fs-6" style="border-top: 2px solid #34495e; padding-top: 8px;">
                                {{ number_format(abs($finalPayable), 2) }}
                            </td>
                        </tr>

                        @if (round(abs($previousBalance), 2) > 0)
                            <tr style="border-bottom: 2px solid #eee;">
                                <td class="text-muted">Prev Bal</td>
                                <td class="text-end text-muted">
                                    {{ number_format(abs($previousBalance), 2) }}
                                    <small>{{ $previousBalance >= 0 ? 'Dr' : 'Cr' }}</small>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Paid</td>
                            <td class="text-end text-success">{{ number_format($paidAmount, 2) }}</td>
                        </tr>
                        @php
                            $finalBal = $previousBalance + $finalPayable - $paidAmount;
                        @endphp
                        <tr class="closing-bal">
                            <td class="fw-bold text-dark py-2">Closing Balance</td>
                            <td class="text-end fw-bold text-dark py-2">
                                {{ number_format(abs($finalBal), 2) }} <span class="text-muted" style="font-size: 11px;">{{ $finalBal >= 0 ? 'Dr' : 'Cr' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="text-end mt-1">
                    <small class="text-muted fst-italic"
                        style="font-size: 10px;">{{ Str::limit($sale->total_amount_Words, 60) }}</small>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
