@extends('admin_panel.layout.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sup-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
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
    }
    .sup-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Form UI */
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
    }
    
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #10b981;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-truck text-primary me-2" style="color: #2563eb !important;"></i>{{ isset($vendor) ? 'Edit Vendor' : 'Add New Vendor' }}</h3>
            <div class="sup-sub">Fill in the vendor details below</div>
        </div>
        <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-1"></i> Back to Vendors
        </a>
    </div>

    {{-- Main Content Card --}}
    <div class="sup-card">
        <form action="{{ url('vendor/store') }}" method="POST">
            @csrf
            <div class="modal-body p-4">
                <input type="hidden" name="id" value="{{ isset($vendor) ? $vendor->id : '' }}" />
                
                <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-info-circle me-1"></i> Basic Information</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold text-dark small">Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="e.g. ABC Chemicals" required value="{{ old('name', isset($vendor) ? $vendor->name : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label fw-semibold text-dark small">Company Name</label>
                        <input type="text" name="company_name" class="form-control px-3 py-2" id="company_name" placeholder="Optional" value="{{ old('company_name', isset($vendor) ? $vendor->company_name : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                </div>

                <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-address-book me-1"></i> Contact Details</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="contact_person" class="form-label fw-semibold text-dark small">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control px-3 py-2" id="contact_person" placeholder="e.g. Ali Khan" value="{{ old('contact_person', isset($vendor) ? $vendor->contact_person : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label fw-semibold text-dark small">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control px-3 py-2" id="phone" placeholder="e.g. 03xx-xxxxxxx" required value="{{ old('phone', isset($vendor) ? $vendor->phone : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label fw-semibold text-dark small">Email</label>
                        <input type="email" name="email" class="form-control px-3 py-2" id="email" placeholder="Optional" value="{{ old('email', isset($vendor) ? $vendor->email : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label fw-semibold text-dark small">Address</label>
                        <textarea name="address" class="form-control px-3 py-2" id="address" rows="2" placeholder="Full Address..." style="border-radius: 10px; border: 1.5px solid #cbd5e1;">{{ old('address', isset($vendor) ? $vendor->address : '') }}</textarea>
                    </div>
                </div>

                <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-file-invoice-dollar me-1"></i> Financials & Business</h6>
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="ntn_number" class="form-label fw-semibold text-dark small">NTN / Tax Number</label>
                        <input type="text" name="ntn_number" class="form-control px-3 py-2" id="ntn_number" placeholder="Optional" value="{{ old('ntn_number', isset($vendor) ? $vendor->ntn_number : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_terms" class="form-label fw-semibold text-dark small">Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-control px-3 py-2" id="payment_terms" placeholder="e.g. 30 Days Credit" value="{{ old('payment_terms', isset($vendor) ? $vendor->payment_terms : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="credit_limit" class="form-label fw-semibold text-dark small">Credit Limit</label>
                        <input type="number" step="0.01" name="credit_limit" class="form-control px-3 py-2" id="credit_limit" placeholder="0.00" value="{{ old('credit_limit', isset($vendor) ? $vendor->credit_limit : '') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="opening_balance" class="form-label fw-semibold text-dark small">Opening Balance</label>
                        <input type="number" step="0.01" name="opening_balance" class="form-control px-3 py-2" id="opening_balance" placeholder="0.00" value="{{ old('opening_balance', isset($vendor) ? $vendor->opening_balance : '') }}" {{ isset($vendor) && $vendor->id ? 'readonly' : '' }} style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        @if(isset($vendor) && $vendor->id)
                        <small class="text-muted mt-1 d-block">Opening balance cannot be modified once set.</small>
                        @endif
                    </div>

                    <div class="col-md-12 mb-1 mt-2 d-flex align-items-center">
                        <label class="form-label fw-semibold text-dark small mb-0 me-3">Status</label>
                        <input type="hidden" name="status" value="0">
                        @php
                            $status = isset($vendor) ? $vendor->status : 1;
                        @endphp
                        <label class="switch mb-0">
                            <input type="checkbox" name="status" id="status" value="1" {{ $status ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span class="ms-2 small {{ $status ? 'text-success' : 'text-danger' }}" id="statusLabel">{{ $status ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-top">
                <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius: 8px;">Cancel</a>
                @canany(['vendors.create', 'vendors.edit'])
                    <button type="submit" class="btn btn-primary px-4 fw-bold save-btn ms-2" style="border-radius: 8px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
                        <i class="fas fa-check me-1"></i> Save Vendor
                    </button>
                @endcanany
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    // Status Toggle Label
    $('#status').on('change', function() {
        if($(this).is(':checked')) {
            $('#statusLabel').text('Active').removeClass('text-danger').addClass('text-success');
        } else {
            $('#statusLabel').text('Inactive').removeClass('text-success').addClass('text-danger');
        }
    });
</script>
@endsection
