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
        margin-bottom: 24px;
        overflow: hidden;
    }
    .sup-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        font-size: 1rem;
        color: #0f172a;
        font-weight: 600;
    }

    .table-custom thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .table-custom tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .scale-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 20px;
    }
    .scale-input {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e40af;
        border: 2px solid #93c5fd;
        border-radius: 8px;
        padding: 8px 12px;
        width: 150px;
        text-align: center;
    }
    .scale-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active { background: #dcfce7; color: #166534; }
    .status-draft { background: #fef9c3; color: #854d0e; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-file-signature text-primary me-2"></i>Formulation: {{ $formulation->formulation_code }}</h3>
            <div class="sup-sub">Recipe details for {{ $formulation->product->item_name ?? 'Unknown Product' }}</div>
        </div>
        <div>
            <a href="{{ route('formulations.index') }}" class="btn btn-light border fw-bold text-dark">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('formulations.edit', $formulation->id) }}" class="btn btn-warning border fw-bold ms-2">
                <i class="fas fa-edit me-1"></i> Edit Formulation
            </a>
        </div>
    </div>

    <div class="row">
        <!-- General Info -->
        <div class="col-md-7">
            <div class="sup-card h-100">
                <div class="sup-card-header">
                    <span><i class="fas fa-info-circle text-primary me-2"></i>General Information</span>
                    <span class="status-badge status-{{ $formulation->status }}">{{ ucfirst($formulation->status) }}</span>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-label">Product Name</div>
                            <div class="info-value text-primary">{{ $formulation->product->item_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Department</div>
                            <div class="info-value">{{ $formulation->department->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Version</div>
                            <div class="info-value">v{{ $formulation->version }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Base Batch Size</div>
                            <div class="info-value" id="baseBatchSize" data-val="{{ $formulation->batch_size }}">{{ (float)$formulation->batch_size }} {{ $formulation->batchUnit->short_name ?? '' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-label">Effective Date</div>
                            <div class="info-value">{{ $formulation->effective_date ? \Carbon\Carbon::parse($formulation->effective_date)->format('d M, Y') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scale / Calculator -->
        <div class="col-md-5">
            <div class="sup-card h-100">
                <div class="sup-card-header">
                    <span><i class="fas fa-calculator text-success me-2"></i>Production Scale Calculator</span>
                </div>
                <div class="p-4">
                    <div class="scale-box text-center">
                        <div class="mb-2 text-dark fw-bold">Enter Target Production Quantity</div>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <input type="number" id="scaleTarget" class="scale-input" value="{{ (float)$formulation->batch_size }}" min="1">
                            <span class="fw-bold fs-5 text-secondary">{{ $formulation->batchUnit->short_name ?? '' }}</span>
                        </div>
                        <div class="mt-3 text-muted" style="font-size: 0.85rem;">
                            The required raw materials and packaging will automatically scale based on this quantity.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Raw Materials -->
    <div class="sup-card mt-4">
        <div class="sup-card-header">
            <span><i class="fas fa-vial text-info me-2"></i>Required Raw Materials</span>
        </div>
        <div class="p-0 table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%">Material Name</th>
                        <th class="text-end">Base Qty</th>
                        <th class="text-end bg-light text-primary">Required Qty</th>
                        <th>Unit</th>
                        <th class="text-center">Waste %</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formulation->rawMaterials as $rm)
                    <tr>
                        <td class="fw-bold">{{ $rm->rawMaterial->name ?? 'N/A' }}</td>
                        <td class="text-end text-muted">{{ (float)$rm->quantity }}</td>
                        <td class="text-end fw-bold text-primary scaled-rm-qty" data-base="{{ $rm->quantity }}">{{ (float)$rm->quantity }}</td>
                        <td>{{ $rm->unit->short_name ?? '' }}</td>
                        <td class="text-center">{{ (float)$rm->waste_percent }}%</td>
                        <td class="text-muted small">{{ $rm->notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Packaging Materials -->
    <div class="sup-card">
        <div class="sup-card-header">
            <span><i class="fas fa-box-open text-warning me-2"></i>Required Packaging Materials</span>
        </div>
        <div class="p-0 table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%">Packaging Item</th>
                        <th class="text-end">Base Qty</th>
                        <th class="text-end bg-light text-primary">Required Qty</th>
                        <th>Unit</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formulation->packagingMaterials as $pm)
                    <tr>
                        <td class="fw-bold">{{ $pm->packagingMaterial->name ?? 'N/A' }}</td>
                        <td class="text-end text-muted">{{ (float)$pm->quantity }}</td>
                        <td class="text-end fw-bold text-primary scaled-pm-qty" data-base="{{ $pm->quantity }}">{{ (float)$pm->quantity }}</td>
                        <td>PCS</td>
                        <td class="text-muted small">{{ $pm->notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($formulation->notes)
    <div class="sup-card">
        <div class="sup-card-header">Formulation Notes</div>
        <div class="p-4 text-muted">
            {!! nl2br(e($formulation->notes)) !!}
        </div>
    </div>
    @endif
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        const baseBatchSize = parseFloat($('#baseBatchSize').data('val')) || 1;

        $('#scaleTarget').on('input', function() {
            let targetSize = parseFloat($(this).val());
            if(isNaN(targetSize) || targetSize <= 0) return;

            let scaleFactor = targetSize / baseBatchSize;

            // Scale Raw Materials
            $('.scaled-rm-qty').each(function() {
                let baseQty = parseFloat($(this).data('base'));
                let scaledQty = baseQty * scaleFactor;
                // Format nicely, max 4 decimals, remove trailing zeros
                $(this).text(parseFloat(scaledQty.toFixed(4)));
            });

            // Scale Packaging Materials (typically whole numbers, but let's allow decimals if setup that way)
            $('.scaled-pm-qty').each(function() {
                let baseQty = parseFloat($(this).data('base'));
                let scaledQty = baseQty * scaleFactor;
                // typically packaging is whole pieces, we'll round up to be safe for PCS
                $(this).text(Math.ceil(scaledQty));
            });
        });
    });
</script>
@endsection
