@extends('admin_panel.layout.app')
@section('content')

<style>
    /* ── POS Stock Adjustment Terminal – Exact POS Sale Interface ── */
    :root {
        --pos-primary:    #4f46e5;
        --pos-primary-lt: #eef2ff;
        --pos-success:    #059669;
        --pos-success-lt: #ecfdf5;
        --pos-danger:     #dc2626;
        --pos-danger-lt:  #fef2f2;
        --pos-warning:    #d97706;
        --pos-warning-lt: #fffbeb;
        --pos-info:       #0284c7;
        --pos-info-lt:    #e0f2fe;
        --pos-border:     #e2e8f0;
        --pos-bg:         #f1f5f9;
        --pos-card-bg:    #ffffff;
        --pos-text:       #0f172a;
        --pos-muted:      #64748b;
        --pos-radius:     12px;
        --pos-shadow:     0 2px 10px rgba(0,0,0,.04);
    }

    .pos-adj-page { background: var(--pos-bg); min-height: calc(100vh - 75px); padding: 16px 0; }
    .pos-adj-page .container-fluid { max-width: 100%; }

    /* Top Bar Header */
    .pos-top-bar {
        background: #ffffff; border-radius: var(--pos-radius); border: 1px solid var(--pos-border);
        box-shadow: var(--pos-shadow); padding: 12px 20px; margin-bottom: 16px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
    }
    .pos-top-bar .title-box { display: flex; align-items: center; gap: 10px; }
    .pos-top-bar .title-icon { width: 38px; height: 38px; border-radius: 9px; background: var(--pos-primary-lt); color: var(--pos-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; }

    /* Main 2-Column POS Layout */
    .pos-grid-layout { display: grid; grid-template-columns: 1fr 420px; gap: 16px; }

    /* Left Side — Product & Variant Search/Grid */
    .pos-products-panel {
        background: var(--pos-card-bg); border-radius: var(--pos-radius);
        border: 1px solid var(--pos-border); box-shadow: var(--pos-shadow);
        padding: 16px; display: flex; flex-direction: column; height: 100%;
    }

    /* Product Search & Category Bar */
    .pos-search-box { position: relative; margin-bottom: 14px; }
    .pos-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--pos-muted); font-size: 14px; pointer-events: none; }
    .pos-search-input { width: 100%; height: 42px; padding-left: 36px; padding-right: 12px; border: 1px solid var(--pos-border); border-radius: 9px; font-size: .9rem; font-weight: 600; outline: none; transition: border-color .15s; }
    .pos-search-input:focus { border-color: var(--pos-primary); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }

    /* Category Filter Pills */
    .cat-pills { display: flex; gap: 6px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 14px; }
    .cat-pill { padding: 5px 12px; border-radius: 20px; border: 1px solid var(--pos-border); background: #fff; font-size: .75rem; font-weight: 600; color: var(--pos-muted); white-space: nowrap; cursor: pointer; transition: all .15s; }
    .cat-pill.active, .cat-pill:hover { background: var(--pos-primary); color: #fff; border-color: var(--pos-primary); }

    /* Product Cards Grid (Matching POS Sale Cards) */
    .pos-items-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px; max-height: calc(100vh - 280px); overflow-y: auto; padding-right: 4px;
    }

    .product-card {
        background: #ffffff !important; border: 1px solid var(--pos-border) !important; border-radius: 10px !important;
        display: flex; flex-direction: column; justify-content: space-between;
        transition: all .15s ease; cursor: pointer; user-select: none; position: relative;
    }
    .product-card:hover { border-color: var(--pos-primary) !important; transform: translateY(-2px); box-shadow: 0 4px 14px rgba(79,70,229,.12); }
    
    .product-card-title { font-weight: 700 !important; font-size: .88rem !important; color: var(--pos-text) !important; line-height: 1.2 !important; margin-bottom: 4px !important; font-family: inherit !important; }
    .product-card-code  { font-size: .72rem !important; color: var(--pos-muted) !important; font-family: monospace !important; }
    .variant-badge      { background: var(--pos-info-lt) !important; color: var(--pos-info) !important; border-radius: 4px !important; padding: 2px 6px !important; font-size: .68rem !important; font-weight: 700 !important; margin-top: 4px !important; display: inline-block !important; }
    .product-card-stock { font-size: .74rem !important; font-weight: 700 !important; background: var(--pos-primary-lt) !important; color: var(--pos-primary) !important; border-radius: 6px !important; padding: 4px 10px !important; margin-top: 8px !important; display: inline-block !important; width: fit-content !important; }

    /* Right Side — Adjustment Cart & Summary */
    .pos-cart-panel {
        background: var(--pos-card-bg); border-radius: var(--pos-radius);
        border: 1px solid var(--pos-border); box-shadow: var(--pos-shadow);
        display: flex; flex-direction: column; height: 100%; overflow: hidden;
    }

    .cart-header { padding: 14px 16px; border-bottom: 1px solid var(--pos-border); background: #fff; display: flex; align-items: center; justify-content: space-between; }
    .cart-header .cart-title { font-size: .9rem; font-weight: 700; color: var(--pos-text); margin: 0; display: flex; align-items: center; gap: 8px; }

    .cart-items-wrap { flex: 1; overflow-y: auto; max-height: calc(100vh - 430px); min-height: 220px; padding: 8px 12px; }

    .cart-item-row {
        background: #fff; border: 1px solid var(--pos-border); border-radius: 8px;
        padding: 10px; margin-bottom: 8px; display: flex; flex-direction: column; gap: 6px;
    }
    .cart-item-head { display: flex; align-items: center; justify-content: space-between; font-weight: 700; font-size: .82rem; }
    .cart-item-sub  { font-size: .72rem; color: var(--pos-muted); }
    .cart-flow      { font-size: .75rem; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 6px 8px; border-radius: 6px; }

    /* POS Totals Box */
    .pos-totals-box { background: #1e293b; color: #fff; padding: 16px; border-top: 1px solid var(--pos-border); }
    .totals-row { display: flex; align-items: center; justify-content: space-between; font-size: .8rem; margin-bottom: 6px; color: #cbd5e1; }
    .totals-row.grand { font-size: 1.05rem; font-weight: 700; color: #38bdf8; border-top: 1px solid #334155; padding-top: 8px; margin-top: 6px; margin-bottom: 0; }

    .btn-submit-pos {
        background: #059669; color: #fff; border: none; border-radius: 9px;
        width: 100%; height: 46px; font-size: .95rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer; transition: background .15s; margin-top: 12px;
    }
    .btn-submit-pos:hover { background: #047857; }
    .btn-submit-pos:disabled { background: #475569; opacity: .6; cursor: not-allowed; }

    /* Stepper controls */
    .qty-controls { display: flex; align-items: center; gap: 2px; }
    .qty-btn {
        width: 26px; height: 26px; border-radius: 5px; border: 1px solid var(--pos-border);
        background: #f8fafc; color: var(--pos-text); font-weight: 700; font-size: 12px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        user-select: none; transition: all .12s;
    }
    .qty-btn:active { transform: scale(0.92); }
    .qty-input { width: 42px; height: 26px; text-align: center; font-weight: 700; font-size: .78rem; border: 1px solid var(--pos-border); border-radius: 5px; outline: none; }

    /* Mobile Responsive Layout */
    @media (max-width: 992px) {
        .pos-grid-layout { grid-template-columns: 1fr; }
        .pos-cart-panel { margin-top: 16px; }
        .pos-items-grid { max-height: 400px; }
    }
</style>

<div class="pos-adj-page">
<div class="container-fluid px-3">

    {{-- Top Bar --}}
    <div class="pos-top-bar">
        <div class="title-box">
            <div class="title-icon"><i class="fas fa-cash-register"></i></div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Stock Adjustment Terminal</h5>
                <small class="text-muted">POS Sale Style Multi-Variant &amp; Piece Adjustment Screen</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- Target Warehouse Selector --}}
            <div class="d-flex align-items-center gap-2">
                <label class="fw-bold text-secondary small text-uppercase mb-0"><i class="fas fa-warehouse text-primary"></i> Warehouse:</label>
                <select id="terminal_warehouse_id" class="form-select form-select-sm fw-bold" style="width:200px; height:36px; border-radius:7px;">
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name ?? $wh->warehouse_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Audit Log Link --}}
            <a href="{{ route('stock_adjustments.index') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3" style="border-radius:7px; height:36px; display:inline-flex; align-items:center; gap:6px;">
                <i class="fas fa-history"></i> Audit Logs
            </a>
        </div>
    </div>

    {{-- Main POS 2-Column Grid --}}
    <div class="pos-grid-layout">

        {{-- LEFT SIDE: Product Cards Grid --}}
        <div class="pos-products-panel">
            
            {{-- Search Input --}}
            <div class="pos-search-box">
                <i class="fas fa-search pos-search-icon"></i>
                <input type="text" id="posProductSearch" class="pos-search-input" placeholder="Type Product Name, Code, or Scan Barcode to filter cards…">
            </div>

            {{-- Category Filter Pills --}}
            <div class="cat-pills">
                <div class="cat-pill active" data-cat="">All Categories</div>
                @foreach($categories as $cat)
                    <div class="cat-pill" data-cat="{{ $cat->id }}">{{ $cat->name }}</div>
                @endforeach
            </div>

            {{-- Product POS Cards Grid --}}
            <div id="posItemsGrid" class="pos-items-grid">
                @foreach($products as $p)
                    @php
                        $variants = [];
                        if ($p->color) {
                            try {
                                $parsed = is_string($p->color) ? json_decode($p->color, true) : $p->color;
                                if (is_array($parsed) && count($parsed) > 0 && (isset($parsed[0]['name']) || isset($parsed[0]['color']))) {
                                    $variants = $parsed;
                                }
                            } catch (\Exception $e) {}
                        }
                        $hasVariants = count($variants) > 0;
                    @endphp

                    <div class="product-card p-3" 
                         data-id="{{ $p->id }}"
                         data-name="{{ $p->item_name }}"
                         data-code="{{ $p->item_code }}"
                         data-cat="{{ $p->category_id }}"
                         data-has-variants="{{ $hasVariants ? 1 : 0 }}"
                         data-variants="{{ json_encode($variants) }}"
                         data-search="{{ strtolower($p->item_name . ' ' . $p->item_code) }}">
                        <div>
                            <div class="product-card-title">{{ $p->item_name }}</div>
                            <div class="product-card-code">{{ $p->item_code }}</div>
                            
                            @if($hasVariants)
                                <div class="variant-badge">
                                    <i class="fa fa-tags me-1"></i> {{ count($variants) }} Variants (Size/Color)
                                </div>
                            @else
                                <div class="text-muted small mt-1">Standard Item</div>
                            @endif
                        </div>

                        <div class="product-card-stock mt-2">
                            <i class="fa fa-hand-pointer me-1"></i> Click to Adjust
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT SIDE: Adjustment Cart & POS Totals Box --}}
        <div class="pos-cart-panel">
            
            {{-- Cart Header --}}
            <div class="cart-header">
                <p class="cart-title">
                    <i class="fas fa-shopping-cart text-primary"></i> Staged Adjustments (<span id="cartCount">0</span>)
                </p>
                <button type="button" id="clearCartBtn" class="btn btn-link btn-sm text-danger text-decoration-none p-0">
                    <i class="fas fa-trash-alt me-1"></i> Clear Cart
                </button>
            </div>

            {{-- Cart Items List --}}
            <div id="cartItemsWrap" class="cart-items-wrap">
                <div id="emptyCartNotice" class="text-center py-5 text-muted">
                    <i class="fas fa-cart-plus fa-3x mb-3" style="color:#cbd5e1;"></i>
                    <p class="mb-0 fw-semibold">No items staged for adjustment</p>
                    <small>Click on any product card on the left to select variants and adjust stock!</small>
                </div>
            </div>

            {{-- Reason / Remarks --}}
            <div class="px-3 pt-2 pb-1 bg-white border-top">
                <label class="fw-bold text-secondary small text-uppercase mb-1">Adjustment Reason / Remarks <span class="text-danger">*</span></label>
                <textarea id="terminal_reason" rows="2" class="form-control form-control-sm" style="border-radius:7px;" placeholder="Specify reason (e.g., 'Wrong variant sold in POS', 'Physical recount', 'Damaged items')" required></textarea>
            </div>

            {{-- POS Totals Box --}}
            <div class="pos-totals-box">
                <div class="totals-row">
                    <span>Products Affected:</span>
                    <strong id="statProductsCount">0</strong>
                </div>
                <div class="totals-row">
                    <span>Variants Adjusted:</span>
                    <strong id="statItemsCount">0 Items</strong>
                </div>
                <div class="totals-row">
                    <span>Total Added (+):</span>
                    <strong class="text-success" id="statAddedQty">+0 Pcs</strong>
                </div>
                <div class="totals-row">
                    <span>Total Deducted (-):</span>
                    <strong class="text-danger" id="statDeductedQty">-0 Pcs</strong>
                </div>
                <div class="totals-row grand">
                    <span>Net Stock Difference:</span>
                    <span id="statNetDiff">0 Pcs</span>
                </div>

                <button type="button" id="submitTerminalBtn" class="btn-submit-pos" disabled>
                    <i class="fas fa-bolt"></i> Process Stock Adjustment (<span id="btnSubmitCount">0</span>)
                </button>
            </div>

        </div>

    </div>

</div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SELECT VARIANT POPUP MODAL (Matching POS Sale Modal)
══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="variantsModal" tabindex="-1" aria-labelledby="variantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
            
            <div class="modal-header border-bottom bg-white px-4 py-3">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="variantsModalLabel">
                        <i class="fas fa-tags text-primary me-2"></i>Select Variant to Adjust
                    </h5>
                    <small class="text-muted">Set action (+ / - / =) and quantity for each variant, then click Add</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size:.85rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Variant Description</th>
                                <th class="text-center">Current Stock</th>
                                <th class="text-center">Action Type</th>
                                <th class="text-center">Adjustment Qty</th>
                                <th class="text-center pe-4">Add to Cart</th>
                            </tr>
                        </thead>
                        <tbody id="variantsModalList">
                            {{-- Dynamically populated --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-white py-2 px-4 border-top">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function () {

    // Staged Cart Array
    let cart = [];

    // Category Filter Pills
    $('.cat-pill').on('click', function () {
        $('.cat-pill').removeClass('active');
        $(this).addClass('active');
        filterGrid();
    });

    // Real-time Search Input Filter
    $('#posProductSearch').on('input', function () {
        filterGrid();
    });

    function filterGrid() {
        let cat    = $('.cat-pill.active').data('cat');
        let search = $.trim($('#posProductSearch').val()).toLowerCase();

        $('.product-card').each(function () {
            let card   = $(this);
            let itemCat= card.data('cat');
            let itemSearch = card.data('search');

            let matchCat = !cat || itemCat == cat;
            let matchSearch = !search || itemSearch.indexOf(search) > -1;

            if (matchCat && matchSearch) {
                card.removeClass('d-none');
            } else {
                card.addClass('d-none');
            }
        });
    }

    // Click on Product Card (Matches POS Sale behavior)
    $('.product-card').on('click', function () {
        let card        = $(this);
        let productId   = card.data('id');
        let productName = card.data('name');
        let productCode = card.data('code');
        let hasVariants = card.data('has-variants') == 1;
        let warehouseId = $('#terminal_warehouse_id').val();

        if (hasVariants) {
            // Fetch live variant stock via AJAX
            $('#variantsModalLabel').text('Select Variant - ' + productName + ' (' + productCode + ')');
            let tbody = $('#variantsModalList');
            tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading variants & live stock...</td></tr>');
            $('#variantsModal').modal('show');

            $.ajax({
                url: "/stock-adjustments/product-variants/" + productId,
                type: "GET",
                data: { warehouse_id: warehouseId },
                success: function (res) {
                    tbody.empty();

                    if (res.has_variants && res.variants.length > 0) {
                        $.each(res.variants, function (i, v) {
                            let row = `
                            <tr data-pid="${productId}" data-pname="${productName}" data-pcode="${productCode}" data-vkey="${v.variant_key}" data-vname="${v.name}" data-stock="${v.current_stock}">
                                <td class="ps-4 fw-bold">
                                    ${v.name}
                                    <div class="text-muted small font-monospace">Size: ${v.size} | Color: ${v.color}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary px-2 py-1" style="font-size:.78rem;">
                                        ${v.current_stock} Pcs
                                    </span>
                                </td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm modal-action-type mx-auto" style="width:110px; font-weight:600; font-size:.78rem;">
                                        <option value="add">🟢 Add (+)</option>
                                        <option value="subtract">🔴 Deduct (-)</option>
                                        <option value="set">🔵 Set (=)</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <div class="qty-controls mx-auto">
                                        <button type="button" class="qty-btn modal-qty-minus">-</button>
                                        <input type="number" step="1" min="1" value="1" class="qty-input modal-qty-val">
                                        <button type="button" class="qty-btn modal-qty-plus">+</button>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <button type="button" class="btn btn-primary btn-sm px-3 modal-add-btn fw-bold">
                                        <i class="fas fa-plus me-1"></i> Add
                                    </button>
                                </td>
                            </tr>`;
                            tbody.append(row);
                        });
                    } else {
                        tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted">No variants found for this product.</td></tr>');
                    }
                }
            });
        } else {
            // Standard Product (No variants) -> Add directly to cart
            $.ajax({
                url: "/stock-adjustments/product-variants/" + productId,
                type: "GET",
                data: { warehouse_id: warehouseId },
                success: function (res) {
                    let curStock = res.total_stock || 0;
                    addItemToCart({
                        cartKey:      productId + '_std',
                        product_id:   productId,
                        product_name: productName,
                        product_code: productCode,
                        variant_key:  '',
                        variant_name: '',
                        type:         'add',
                        qty:          1,
                        cur_stock:    curStock
                    });
                }
            });
        }
    });

    // Modal Stepper + / - Click Handlers
    $(document).on('click', '.modal-qty-plus', function () {
        let input = $(this).siblings('.modal-qty-val');
        let val = parseFloat(input.val()) || 0;
        input.val(val + 1);
    });

    $(document).on('click', '.modal-qty-minus', function () {
        let input = $(this).siblings('.modal-qty-val');
        let val = parseFloat(input.val()) || 0;
        if (val > 1) input.val(val - 1);
    });

    // Add Variant to Cart from Modal Row
    $(document).on('click', '.modal-add-btn', function () {
        let row      = $(this).closest('tr');
        let pid      = row.data('pid');
        let pname    = row.data('pname');
        let pcode    = row.data('pcode');
        let vkey     = row.data('vkey');
        let vname    = row.data('vname');
        let curStock = parseFloat(row.data('stock')) || 0;
        let type     = row.find('.modal-action-type').val();
        let qty      = parseFloat(row.find('.modal-qty-val').val()) || 0;

        if (qty <= 0) {
            Swal.fire('Invalid Qty', 'Please enter quantity > 0.', 'warning');
            return;
        }

        addItemToCart({
            cartKey:     pid + '_' + vkey,
            product_id:   pid,
            product_name: pname,
            product_code: pcode,
            variant_key:  vkey,
            variant_name: vname,
            type:         type,
            qty:          qty,
            cur_stock:    curStock
        });

        Swal.fire({ toast:true, position:'top-end', icon:'success', title: vname + ' added to adjustment cart!', showConfirmButton:false, timer:1200 });
    });

    function addItemToCart(item) {
        let existingIndex = cart.findIndex(c => c.cartKey === item.cartKey);
        if (existingIndex > -1) {
            cart[existingIndex] = item;
        } else {
            cart.push(item);
        }
        renderCart();
    }

    // Render Cart Items & Calculate POS Totals Box
    function renderCart() {
        let container = $('#cartItemsWrap');
        container.empty();

        if (cart.length === 0) {
            container.append(`
            <div id="emptyCartNotice" class="text-center py-5 text-muted">
                <i class="fas fa-cart-plus fa-3x mb-3" style="color:#cbd5e1;"></i>
                <p class="mb-0 fw-semibold">No items staged for adjustment</p>
                <small>Click on any product card on the left to select variants and adjust stock!</small>
            </div>`);

            $('#cartCount, #statItemsCount, #btnSubmitCount').text('0');
            $('#statProductsCount').text('0');
            $('#statAddedQty').text('+0 Pcs');
            $('#statDeductedQty').text('-0 Pcs');
            $('#statNetDiff').text('0 Pcs');
            $('#submitTerminalBtn').prop('disabled', true);
            return;
        }

        let totalAdded = 0;
        let totalDeducted = 0;
        let uniqueProducts = new Set();

        $.each(cart, function (index, item) {
            uniqueProducts.add(item.product_id);

            let expStock = item.cur_stock;
            let typeBadge = '';
            if (item.type === 'add') {
                typeBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle">+ Add</span>';
                expStock  = item.cur_stock + item.qty;
                totalAdded += item.qty;
            } else if (item.type === 'subtract') {
                typeBadge = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">- Deduct</span>';
                expStock  = Math.max(0, item.cur_stock - item.qty);
                totalDeducted += item.qty;
            } else {
                typeBadge = '<span class="badge bg-info-subtle text-info border border-info-subtle">= Set</span>';
                expStock  = Math.max(0, item.qty);
                let diff = expStock - item.cur_stock;
                if (diff > 0) totalAdded += diff;
                else totalDeducted += Math.abs(diff);
            }

            let rowHtml = `
            <div class="cart-item-row">
                <div class="cart-item-head">
                    <div>
                        <span>${item.variant_name || item.product_name}</span>
                        <div class="cart-item-sub">${item.product_code}</div>
                    </div>
                    <button type="button" class="btn btn-link btn-sm text-danger remove-cart-item p-0" data-key="${item.cartKey}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <div class="cart-flow">
                    <div>${typeBadge} <strong>${item.qty} Pcs</strong></div>
                    <div>Stock: <span class="text-muted">${item.cur_stock}</span> &rarr; <strong class="text-success">${expStock} Pcs</strong></div>
                </div>
            </div>`;

            container.append(rowHtml);
        });

        let netDiff = totalAdded - totalDeducted;
        let netStr  = (netDiff > 0 ? '+' : '') + netDiff.toLocaleString() + ' Pcs';

        $('#cartCount, #statItemsCount, #btnSubmitCount').text(cart.length);
        $('#statProductsCount').text(uniqueProducts.size);
        $('#statAddedQty').text('+' + totalAdded.toLocaleString() + ' Pcs');
        $('#statDeductedQty').text('-' + totalDeducted.toLocaleString() + ' Pcs');
        $('#statNetDiff').text(netStr);

        $('#submitTerminalBtn').prop('disabled', false);
    }

    // Remove Item from Cart
    $(document).on('click', '.remove-cart-item', function () {
        let key = $(this).data('key');
        cart = cart.filter(item => item.cartKey !== key);
        renderCart();
    });

    // Clear Entire Cart
    $('#clearCartBtn').on('click', function () {
        cart = [];
        renderCart();
    });

    // Submit Terminal Adjustment AJAX
    $('#submitTerminalBtn').on('click', function () {
        let warehouseId  = $('#terminal_warehouse_id').val();
        let globalReason = $('#terminal_reason').val();

        if (cart.length === 0) {
            Swal.fire('Empty Cart', 'Please add at least one item to adjust.', 'warning');
            return;
        }

        if (!globalReason) {
            Swal.fire('Reason Required', 'Please specify a reason/remarks for this stock adjustment.', 'warning');
            $('#terminal_reason').focus();
            return;
        }

        let btn = $(this);
        let origHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Processing Adjustment...').prop('disabled', true);

        $.ajax({
            url: "{{ route('stock_adjustments.store_batch') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                warehouse_id: warehouseId,
                global_reason: globalReason,
                items: cart
            },
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Stock Adjustment Processed!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('stock_adjustments.index') }}";
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed to process adjustment.', 'error');
                    btn.html(origHtml).prop('disabled', false);
                }
            },
            error: function (xhr) {
                btn.html(origHtml).prop('disabled', false);
                let msg = xhr.responseJSON?.message || 'Server error occurred.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

});
</script>
@endsection
