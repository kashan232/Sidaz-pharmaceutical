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
    .sup-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .sup-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    .sup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .sup-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        color: #0f172a;
    }
    .sup-card-body {
        padding: 24px;
    }

    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 0.95rem;
        color: #0f172a;
        font-weight: 500;
    }

    .table-custom thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .table-custom tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .totals-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    .total-row.grand-total {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #cbd5e1;
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
    }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-file-invoice text-primary me-2"></i>Purchase Invoice #{{ $purchase->invoice_no }}</h3>
            <div class="sup-sub">View details for this material purchase</div>
        </div>
        <div>
            <a href="{{ route('material-purchases.index') }}" class="btn btn-light border fw-bold text-dark">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('material-purchases.edit', $purchase->id) }}" class="btn btn-warning border fw-bold ms-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Vendor & Invoice Info -->
        <div class="col-md-8">
            <div class="sup-card h-100">
                <div class="sup-card-header">
                    <span><i class="fas fa-info-circle text-primary me-2"></i>General Information</span>
                    <span class="badge {{ $purchase->status == 'completed' ? 'bg-success' : 'bg-secondary' }}">{{ strtoupper($purchase->status) }}</span>
                </div>
                <div class="sup-card-body">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-label">Vendor / Supplier</div>
                            <div class="info-value fw-bold text-primary">{{ $purchase->vendor->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Purchase Date</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Purchase Type</div>
                            <div class="info-value">{{ $purchase->purchase_type }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Payment Method</div>
                            <div class="info-value">{{ $purchase->payment_method }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">{{ $purchase->payment_status }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transport Info -->
        <div class="col-md-4">
            <div class="sup-card h-100">
                <div class="sup-card-header">
                    <span><i class="fas fa-truck text-secondary me-2"></i>Transport Details</span>
                </div>
                <div class="sup-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-label">Transport Name</div>
                            <div class="info-value">{{ $purchase->transport_name ?: '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Driver Name & Contact</div>
                            <div class="info-value">{{ $purchase->driver_name ?: '-' }} <br> <small class="text-muted">{{ $purchase->driver_contact ?: '' }}</small></div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Vehicle No</div>
                            <div class="info-value">{{ $purchase->vehicle_no ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="sup-card mt-4">
        <div class="sup-card-header">
            <span><i class="fas fa-box text-warning me-2"></i>Purchased Items</span>
        </div>
        <div class="p-0 table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Item Details</th>
                        <th>Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Tax</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $item->item->name ?? 'Unknown' }}</div>
                            @if($item->batch_no)
                            <small class="text-muted">Batch: {{ $item->batch_no }}</small>
                            @endif
                        </td>
                        <td>
                            @if($item->item_type == \App\Models\RawMaterial::class)
                                <span class="badge bg-light text-dark border">Raw Material</span>
                            @else
                                <span class="badge bg-light text-dark border">Packaging</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->qty, 2) }} {{ $item->item->unit->short_name ?? '' }}</td>
                        <td class="text-end">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">Rs. {{ number_format($item->discount, 2) }}</td>
                        <td class="text-end">Rs. {{ number_format($item->tax, 2) }}</td>
                        <td class="text-end fw-bold">Rs. {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary & Remarks -->
    <div class="row mt-4">
        <div class="col-md-7">
            @if($purchase->remarks)
            <div class="sup-card">
                <div class="sup-card-header">Remarks</div>
                <div class="sup-card-body">
                    {{ $purchase->remarks }}
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-5">
            <div class="totals-box shadow-sm">
                <div class="total-row">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-bold">Rs. {{ number_format($purchase->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="text-muted">Total Discount:</span>
                    <span class="text-danger">- Rs. {{ number_format($purchase->total_discount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="text-muted">Total Tax:</span>
                    <span>+ Rs. {{ number_format($purchase->total_tax, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="text-muted">Transport Charges:</span>
                    <span>+ Rs. {{ number_format($purchase->transport_charges, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span>Grand Total:</span>
                    <span class="text-primary">Rs. {{ number_format($purchase->total_amount, 2) }}</span>
                </div>
                
                <div class="total-row mt-3 pt-2 border-top">
                    <span class="text-muted">Paid Amount:</span>
                    <span class="text-success fw-bold">Rs. {{ number_format($purchase->paid_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="text-muted">Balance:</span>
                    <span class="text-danger fw-bold">Rs. {{ number_format($purchase->balance_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
