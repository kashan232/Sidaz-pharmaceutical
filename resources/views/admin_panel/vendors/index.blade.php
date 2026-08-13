@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    });
    </script>
@endif

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

    /* Action Buttons Hub */
    .vendor-action-hub {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-vendor-sub {
        background: #ffffff;
        color: #475569 !important;
        border: 1.5px solid #cbd5e1;
        padding: 9px 16px;
        font-size: 0.84rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-vendor-sub:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a !important;
    }

    /* Stat Cards */
    .sup-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .sup-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .sup-stat-icon.active {
        background: #dcfce7;
        color: #16a34a;
    }
    .sup-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .sup-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-sup-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-sup-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    /* Card & Table */
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

    .sup-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #default-datatable {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    #default-datatable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    #default-datatable tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #default-datatable tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges */
    .sup-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }
    .contact-badge {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .finance-badge {
        background: #fdf4ff;
        color: #c026d3;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active {
        background: #dcfce7;
        color: #166534;
    }
    .status-inactive {
        background: #fef2f2;
        color: #991b1b;
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
            <h3 class="sup-title"><i class="fas fa-truck text-primary me-2" style="color: #2563eb !important;"></i>Vendor / Supplier Management</h3>
            <div class="sup-sub">Manage suppliers, vendors, ledgers and payments</div>
        </div>
        <div class="vendor-action-hub">
            @can('vendors.create')
                <a href="{{ route('vendors.create') }}" class="btn-sup-primary">
                    <i class="fas fa-plus"></i> Add Vendor
                </a>
            @endcan
            <a href="{{ url('vendors-ledger') }}" class="btn-vendor-sub">
                <i class="fas fa-book me-1"></i> Ledger
            </a>
            <a href="{{ route('vendor.payments') }}" class="btn-vendor-sub">
                <i class="fas fa-money-check-alt me-1"></i> Payments
            </a>
            <a href="{{ url('vendor/bilties') }}" class="btn-vendor-sub">
                <i class="fas fa-shipping-fast me-1"></i> Bilty
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="sup-stat-card">
                <div class="sup-stat-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="sup-stat-val">{{ count($vendors) }}</div>
                    <div class="sup-stat-lbl">Total Vendors</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="sup-stat-card">
                <div class="sup-stat-icon active"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="sup-stat-val">{{ $vendors->where('status', 1)->count() }}</div>
                    <div class="sup-stat-lbl">Active Vendors</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="sup-card">
        <div class="sup-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Vendors & Suppliers</div>
            <div class="text-muted small">Showing {{ count($vendors) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="sup-table-wrap p-3">
            <table class="table align-middle datanew">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Id</th>
                        <th class="text-start">Vendor Info</th>
                        <th class="text-start">Contact</th>
                        <th class="text-start">Financials</th>
                        <th class="text-center">Status</th>
                        <!-- Hidden headers to match hidden TDs for DataTables -->
                        <th class="d-none">Company Name</th>
                        <th class="d-none">Email</th>
                        <th class="d-none">Address</th>
                        <th class="d-none">NTN Number</th>
                        <th class="d-none">Payment Terms</th>
                        
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $v)
                        <tr>
                            <td class="text-center id"><span class="sup-id-badge">#{{ $v->id }}</span></td>
                            
                            <td class="text-start">
                                <div class="name fw-bold text-dark">{{ $v->name }}</div>
                                @if($v->company_name)
                                    <div class="text-muted small"><i class="fas fa-building me-1"></i>{{ $v->company_name }}</div>
                                @endif
                            </td>
                            
                            <td class="text-start">
                                @if($v->contact_person)
                                    <div class="contact_person fw-semibold text-dark"><i class="fas fa-user-tie me-1 text-muted"></i>{{ $v->contact_person }}</div>
                                @else
                                    <div class="contact_person d-none"></div>
                                @endif
                                <div class="phone text-muted small"><i class="fas fa-phone-alt me-1"></i>{{ $v->phone }}</div>
                            </td>

                            <td class="text-start">
                                <div class="finance-badge mb-1" title="Credit Limit">Limit: Rs. <span class="credit_limit">{{ number_format($v->credit_limit, 2) }}</span></div>
                                <div class="text-muted small" title="Opening Balance">OB: Rs. <span class="opening_balance">{{ number_format($v->opening_balance, 2) }}</span></div>
                            </td>

                            <td class="text-center status" data-status="{{ $v->status }}">
                                @if($v->status)
                                    <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Inactive</span>
                                @endif
                            </td>

                            <!-- Hidden Data for Edit Modal -->
                            <td class="d-none company_name">{{ $v->company_name }}</td>
                            <td class="d-none email">{{ $v->email }}</td>
                            <td class="d-none address">{{ $v->address }}</td>
                            <td class="d-none ntn_number">{{ $v->ntn_number }}</td>
                            <td class="d-none payment_terms">{{ $v->payment_terms }}</td>

                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('vendors.edit', $v->id),
                                    'deleteRoute' => url('vendor/delete/' . $v->id),
                                    'editIsLink' => true,
                                    'permissions' => [
                                        'edit' => 'vendors.edit',
                                        'delete' => 'vendors.delete',
                                    ],
                                    'dataId' => $v->id,
                                    'deleteMsg' => 'Are you sure you want to delete this vendor?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datanew')) {
            $('.datanew').DataTable().destroy();
        }
        $('.datanew').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search vendors..."
            },
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });
    });
</script>
@endsection
