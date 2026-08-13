@extends('admin_panel.layout.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sup-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }
    .sup-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .sup-title { font-weight: 800; font-size: 1.35rem; color: #0f172a; margin-bottom: 2px; letter-spacing: -0.02em; }
    .sup-sub { font-size: 0.82rem; color: #64748b; }

    .sup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
    }

    .table-items th { background: #f8fafc; color: #475569; font-size: 0.75rem; text-transform: uppercase; }
    .table-items td { vertical-align: middle; }
    
    .btn-add-row {
        background: #eff6ff; color: #2563eb; border: 1px dashed #93c5fd; padding: 8px 16px; font-weight: 600; border-radius: 8px; transition: 0.2s;
    }
    .btn-add-row:hover { background: #dbeafe; }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex align-items-center justify-content-between">
        <div>
            <h3 class="sup-title"><i class="fas fa-edit text-primary me-2"></i>Edit Material Purchase</h3>
            <div class="sup-sub">Edit purchase invoice {{ $purchase->invoice_no }}</div>
        </div>
        <a href="{{ route('material-purchases.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="sup-card">
        <form action="{{ route('material-purchases.update', $purchase->id) }}" method="POST" id="purchaseForm">
            @csrf
            <div class="p-4">
                
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-invoice me-1"></i> Invoice Details</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Invoice No</label>
                        <input type="text" name="invoice_no" class="form-control" value="{{ $purchase->invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Vendor <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-control select2" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}" {{ $purchase->vendor_id == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Purchase Type <span class="text-danger">*</span></label>
                        <select name="purchase_type" id="purchase_type" class="form-control select2" required onchange="filterItems()">
                            <option value="Raw Material" {{ $purchase->purchase_type == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                            <option value="Packaging" {{ $purchase->purchase_type == 'Packaging' ? 'selected' : '' }}>Packaging Material</option>
                            <option value="Mixed" {{ $purchase->purchase_type == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                        </select>
                    </div>
                </div>

                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-boxes me-1"></i> Purchase Items</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-items" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Item</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 8%;">Unit</th>
                                <th style="width: 10%;">Price</th>
                                <th style="width: 8%;">Disc</th>
                                <th style="width: 8%;">Tax</th>
                                <th style="width: 10%;">Batch</th>
                                <th style="width: 12%;">Dates (Mfg/Exp)</th>
                                <th style="width: 9%;">Total</th>
                                <th style="width: 5%;" class="text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            @foreach($purchase->items as $idx => $item)
                            <tr class="item-row">
                                <td>
                                    <select name="item_type[]" class="d-none row-item-type"><option value="{{ class_basename($item->item_type) }}"></option></select>
                                    <select name="item_id[]" class="form-control select2 item-select" required onchange="updateRowItemType(this)" data-selected-id="{{ $item->item_id }}" data-selected-type="{{ class_basename($item->item_type) }}">
                                        <option value="">Select Item</option>
                                    </select>
                                </td>
                                <td><input type="number" step="0.0001" name="qty[]" class="form-control item-qty form-control-sm" value="{{ $item->qty }}" required oninput="calcSubtotal(this)"></td>
                                <td class="text-center"><span class="badge bg-secondary item-unit" style="font-size:11px;">-</span></td>
                                <td><input type="number" step="0.01" name="unit_price[]" class="form-control item-price form-control-sm" value="{{ $item->unit_price }}" required oninput="calcSubtotal(this)"></td>
                                <td><input type="number" step="0.01" name="discount[]" class="form-control item-disc form-control-sm" value="{{ $item->discount }}" oninput="calcSubtotal(this)"></td>
                                <td><input type="number" step="0.01" name="tax[]" class="form-control item-tax form-control-sm" value="{{ $item->tax }}" oninput="calcSubtotal(this)"></td>
                                <td><input type="text" name="batch_no[]" class="form-control form-control-sm" value="{{ $item->batch_no }}" placeholder="Batch"></td>
                                <td>
                                    <input type="date" name="mfg_date[]" class="form-control form-control-sm mb-1" value="{{ $item->mfg_date }}" title="Mfg Date">
                                    <input type="date" name="exp_date[]" class="form-control form-control-sm" value="{{ $item->exp_date }}" title="Exp Date">
                                </td>
                                <td><input type="text" class="form-control item-subtotal bg-light fw-bold form-control-sm" value="{{ $item->subtotal }}" data-raw-sub="{{ $item->qty * $item->unit_price }}" data-raw-disc="{{ $item->discount }}" data-raw-tax="{{ $item->tax }}" readonly></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger text-white border-0 remove-row" style="padding: 2px 6px;"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn-add-row" onclick="addRow()"><i class="fas fa-plus me-1"></i> Add Another Item</button>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-truck me-1"></i> Transport & Remarks</h6>
                        <div class="row g-2">
                            <div class="col-md-3"><input type="text" name="transport_name" class="form-control" value="{{ $purchase->transport_name }}" placeholder="Transport Name"></div>
                            <div class="col-md-3"><input type="text" name="driver_name" class="form-control" value="{{ $purchase->driver_name }}" placeholder="Driver Name"></div>
                            <div class="col-md-3"><input type="text" name="vehicle_no" class="form-control" value="{{ $purchase->vehicle_no }}" placeholder="Vehicle No"></div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="transport_charges" id="transport_charges" class="form-control" value="{{ $purchase->transport_charges }}" placeholder="Transport Charges (Rs)" oninput="calcTotal()">
                            </div>
                            <div class="col-md-12"><textarea name="remarks" class="form-control mt-2" rows="2" placeholder="Remarks...">{{ $purchase->remarks }}</textarea></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-money-bill-wave me-1"></i> Payment Summary</h6>
                        <div class="bg-light p-3 rounded border">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark small">Subtotal:</span>
                                <span class="fw-bold" id="lblSubtotal">Rs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-danger small">Total Discount:</span>
                                <span class="fw-bold text-danger" id="lblDiscount">- Rs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-info small">Total Tax:</span>
                                <span class="fw-bold text-info" id="lblTax">+ Rs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Transport:</span>
                                <span class="fw-bold text-secondary" id="lblTransport">+ Rs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 border-top pt-2">
                                <span class="fw-bold text-dark">Grand Total:</span>
                                <span class="fw-bold text-primary" id="lblTotal" style="font-size: 1.1rem;">Rs. 0.00</span>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control select2 form-select-sm">
                                    <option value="Credit" {{ $purchase->payment_method == 'Credit' ? 'selected' : '' }}>Credit (Unpaid)</option>
                                    <option value="Cash" {{ $purchase->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Bank" {{ $purchase->payment_method == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Cheque" {{ $purchase->payment_method == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                </select>
                            </div>
                            
                            <div class="mb-2 {{ $purchase->payment_method == 'Credit' ? 'd-none' : '' }}" id="paymentAccountDiv">
                                <label class="form-label small fw-bold">Payment Account</label>
                                <select name="payment_account_id" class="form-control select2 form-select-sm">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label small fw-bold">Paid Amount</label>
                                <input type="number" step="0.01" name="paid_amount" id="paidAmount" class="form-control form-control-sm" value="{{ $purchase->paid_amount }}" oninput="calcTotal()" {{ $purchase->payment_method == 'Credit' ? 'readonly' : '' }}>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">Balance Due:</span>
                                <span class="fw-bold text-danger" id="lblBalance">Rs. 0.00</span>
                            </div>
                            <input type="hidden" name="payment_status" id="payment_status" value="Pending">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-light px-4 py-3 border-top d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small"><i class="fas fa-info-circle"></i> Drafts do not affect stock or accounting.</span>
                </div>
                <div>
                    <input type="hidden" name="invoice_status" id="invoice_status" value="completed">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-bold me-2" onclick="submitAs('draft')">
                        <i class="fas fa-save me-1"></i> Save as Draft
                    </button>
                    <button type="button" class="btn btn-primary px-5 fw-bold" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none; border-radius: 8px;" onclick="submitAs('completed')">
                        <i class="fas fa-check me-1"></i> Complete Purchase
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    function submitAs(status) {
        $('#invoice_status').val(status);
        $('#purchaseForm').submit();
    }

    const rawMaterials = @json($rawMaterials);
    const packagingMaterials = @json($packagingMaterials);

    function filterItems() {
        const type = $('#purchase_type').val();
        let options = '<option value="">Select Item</option>';
        
        if (type === 'Raw Material' || type === 'Mixed') {
            if(type === 'Mixed') options += '<optgroup label="Raw Materials">';
            rawMaterials.forEach(item => {
                let unitName = item.unit ? item.unit.name : '-';
                options += `<option value="${item.id}" data-type="RawMaterial" data-unit="${unitName}">${item.name} (${item.code})</option>`;
            });
            if(type === 'Mixed') options += '</optgroup>';
        }
        
        if (type === 'Packaging' || type === 'Mixed') {
            if(type === 'Mixed') options += '<optgroup label="Packaging Materials">';
            packagingMaterials.forEach(item => {
                let unitName = item.unit ? item.unit.name : '-';
                options += `<option value="${item.id}" data-type="PackagingMaterial" data-unit="${unitName}">${item.name} (${item.code})</option>`;
            });
            if(type === 'Mixed') options += '</optgroup>';
        }
        
        $('.item-select').each(function() {
            const currentVal = $(this).val() || $(this).data('selected-id');
            const requiredType = $(this).data('selected-type');
            
            // If select2 is initialized, destroy it first to update options cleanly
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
            $(this).html(options);
            
            if(currentVal) {
                // If we need to match type as well (since same ID might exist in both Raw/Packaging)
                if(requiredType) {
                    let opt = $(this).find(`option[value="${currentVal}"][data-type="${requiredType}"]`);
                    if(opt.length > 0) {
                        $(this).val(currentVal);
                        $(this).closest('tr').find('.item-unit').text(opt.data('unit') || '-');
                    }
                } else {
                    if($(this).find(`option[value="${currentVal}"]`).length > 0) {
                        $(this).val(currentVal);
                    }
                }
            }
            // Re-initialize select2
            $(this).select2({ width: '100%' });
        });
    }

    function updateRowItemType(selectElem) {
        const selectedOpt = $(selectElem).find('option:selected');
        const itemType = selectedOpt.data('type');
        const unit = selectedOpt.data('unit') || '-';
        $(selectElem).closest('tr').find('.row-item-type option').val(itemType);
        $(selectElem).closest('tr').find('.item-unit').text(unit);
    }

    function addRow() {
        const tr = `
        <tr class="item-row">
            <td>
                <select name="item_type[]" class="d-none row-item-type"><option value="RawMaterial"></option></select>
                <select name="item_id[]" class="form-control select2 item-select" required onchange="updateRowItemType(this)"></select>
            </td>
            <td><input type="number" step="0.0001" name="qty[]" class="form-control item-qty form-control-sm" required oninput="calcSubtotal(this)"></td>
            <td class="text-center"><span class="badge bg-secondary item-unit" style="font-size:11px;">-</span></td>
            <td><input type="number" step="0.01" name="unit_price[]" class="form-control item-price form-control-sm" required oninput="calcSubtotal(this)"></td>
            <td><input type="number" step="0.01" name="discount[]" class="form-control item-disc form-control-sm" value="0" oninput="calcSubtotal(this)"></td>
            <td><input type="number" step="0.01" name="tax[]" class="form-control item-tax form-control-sm" value="0" oninput="calcSubtotal(this)"></td>
            <td><input type="text" name="batch_no[]" class="form-control form-control-sm" placeholder="Batch"></td>
            <td>
                <input type="date" name="mfg_date[]" class="form-control form-control-sm mb-1" title="Mfg Date">
                <input type="date" name="exp_date[]" class="form-control form-control-sm" title="Exp Date">
            </td>
            <td><input type="text" class="form-control item-subtotal bg-light fw-bold form-control-sm" readonly></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger text-white border-0 remove-row" style="padding: 2px 6px;"><i class="fas fa-times"></i></button></td>
        </tr>`;
        $('#itemsBody').append(tr);
        filterItems(); // Re-populate new select
    }

    $(document).on('click', '.remove-row', function() {
        if ($('.item-row').length > 1) {
            // Destroy select2 before removing to prevent memory leaks
            $(this).closest('tr').find('.select2').select2('destroy');
            $(this).closest('tr').remove();
            calcTotal();
        }
    });

    function calcSubtotal(elem) {
        const tr = $(elem).closest('tr');
        const qty = parseFloat(tr.find('.item-qty').val()) || 0;
        const price = parseFloat(tr.find('.item-price').val()) || 0;
        const disc = parseFloat(tr.find('.item-disc').val()) || 0;
        const tax = parseFloat(tr.find('.item-tax').val()) || 0;
        
        const sub = (qty * price) - disc + tax;
        tr.find('.item-subtotal').val(sub.toFixed(2));
        tr.find('.item-subtotal').data('raw-sub', qty * price);
        tr.find('.item-subtotal').data('raw-disc', disc);
        tr.find('.item-subtotal').data('raw-tax', tax);
        calcTotal();
    }

    function calcTotal() {
        let grandTotal = 0;
        let totalSubtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        
        $('.item-subtotal').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
            totalSubtotal += parseFloat($(this).data('raw-sub')) || 0;
            totalDiscount += parseFloat($(this).data('raw-disc')) || 0;
            totalTax += parseFloat($(this).data('raw-tax')) || 0;
        });
        
        const transport = parseFloat($('#transport_charges').val()) || 0;
        grandTotal += transport;
        
        const paid = parseFloat($('#paidAmount').val()) || 0;
        const bal = grandTotal - paid;
        
        $('#lblSubtotal').text('Rs. ' + totalSubtotal.toFixed(2));
        $('#lblDiscount').text('- Rs. ' + totalDiscount.toFixed(2));
        $('#lblTax').text('+ Rs. ' + totalTax.toFixed(2));
        $('#lblTransport').text('+ Rs. ' + transport.toFixed(2));
        $('#lblTotal').text('Rs. ' + grandTotal.toFixed(2));
        $('#lblBalance').text('Rs. ' + bal.toFixed(2));
        
        if (paid === 0) $('#payment_status').val('Pending');
        else if (paid >= grandTotal) $('#payment_status').val('Paid');
        else $('#payment_status').val('Partial');
    }

    $('#payment_method').on('change', function() {
        if ($(this).val() === 'Credit') {
            $('#paidAmount').val(0).prop('readonly', true);
            $('#paymentAccountDiv').addClass('d-none');
        } else {
            $('#paidAmount').prop('readonly', false);
            $('#paymentAccountDiv').removeClass('d-none');
        }
        calcTotal();
    });

    $(document).ready(function() {
        // Initialize all select2 elements
        $('.select2').select2({ width: '100%' });
        
        filterItems();
        calcTotal();
    });
</script>
@endsection
