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
        background: #eff6ff; /* Blue for Suppliers */
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
            <h3 class="sup-title"><i class="fas fa-truck text-primary me-2" style="color: #2563eb !important;"></i>Supplier Management</h3>
            <div class="sup-sub">Manage suppliers and vendors for raw and packaging materials</div>
        </div>
        @can('suppliers.create')
            <button type="button" class="btn-sup-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Add Supplier
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="sup-stat-card">
                <div class="sup-stat-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="sup-stat-val">{{ count($suppliers) }}</div>
                    <div class="sup-stat-lbl">Total Suppliers</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="sup-stat-card">
                <div class="sup-stat-icon active"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="sup-stat-val">{{ $suppliers->where('status', 1)->count() }}</div>
                    <div class="sup-stat-lbl">Active Suppliers</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="sup-card">
        <div class="sup-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Suppliers</div>
            <div class="text-muted small">Showing {{ count($suppliers) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="sup-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Id</th>
                        <th class="text-start">Supplier Info</th>
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
                    @foreach ($suppliers as $sup)
                        <tr>
                            <td class="text-center id"><span class="sup-id-badge">#{{ $sup->id }}</span></td>
                            
                            <td class="text-start">
                                <div class="name fw-bold text-dark">{{ $sup->name }}</div>
                                @if($sup->company_name)
                                    <div class="text-muted small"><i class="fas fa-building me-1"></i>{{ $sup->company_name }}</div>
                                @endif
                            </td>
                            
                            <td class="text-start">
                                @if($sup->contact_person)
                                    <div class="contact_person fw-semibold text-dark"><i class="fas fa-user-tie me-1 text-muted"></i>{{ $sup->contact_person }}</div>
                                @else
                                    <div class="contact_person d-none"></div>
                                @endif
                                <div class="phone text-muted small"><i class="fas fa-phone-alt me-1"></i>{{ $sup->phone }}</div>
                            </td>

                            <td class="text-start">
                                <div class="finance-badge mb-1" title="Credit Limit">Limit: Rs. <span class="credit_limit">{{ number_format($sup->credit_limit, 2) }}</span></div>
                                <div class="text-muted small" title="Opening Balance">OB: Rs. <span class="opening_balance">{{ number_format($sup->opening_balance, 2) }}</span></div>
                            </td>

                            <td class="text-center status" data-status="{{ $sup->status }}">
                                @if($sup->status)
                                    <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Inactive</span>
                                @endif
                            </td>

                            <!-- Hidden Data for Edit Modal -->
                            <td class="d-none company_name">{{ $sup->company_name }}</td>
                            <td class="d-none email">{{ $sup->email }}</td>
                            <td class="d-none address">{{ $sup->address }}</td>
                            <td class="d-none ntn_number">{{ $sup->ntn_number }}</td>
                            <td class="d-none payment_terms">{{ $sup->payment_terms }}</td>

                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('suppliers.store'),
                                    'deleteRoute' => route('suppliers.delete', $sup->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'suppliers.edit',
                                        'delete' => 'suppliers.delete',
                                    ],
                                    'deleteMsg' => 'Are you sure you want to delete this supplier?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add/Edit Supplier Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-truck text-primary me-2" style="color: #2563eb !important;"></i><span id="modalTitleText">Add Supplier</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-info-circle me-1"></i> Basic Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold text-dark small">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="e.g. ABC Chemicals" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label fw-semibold text-dark small">Company Name</label>
                            <input type="text" name="company_name" class="form-control px-3 py-2" id="company_name" placeholder="Optional" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-address-book me-1"></i> Contact Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="contact_person" class="form-label fw-semibold text-dark small">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control px-3 py-2" id="contact_person" placeholder="e.g. Ali Khan" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label fw-semibold text-dark small">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control px-3 py-2" id="phone" placeholder="e.g. 03xx-xxxxxxx" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label fw-semibold text-dark small">Email</label>
                            <input type="email" name="email" class="form-control px-3 py-2" id="email" placeholder="Optional" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label fw-semibold text-dark small">Address</label>
                            <textarea name="address" class="form-control px-3 py-2" id="address" rows="2" placeholder="Full Address..." style="border-radius: 10px; border: 1.5px solid #cbd5e1;"></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3" style="color: #2563eb !important;"><i class="fas fa-file-invoice-dollar me-1"></i> Financials & Business</h6>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="ntn_number" class="form-label fw-semibold text-dark small">NTN / Tax Number</label>
                            <input type="text" name="ntn_number" class="form-control px-3 py-2" id="ntn_number" placeholder="Optional" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_terms" class="form-label fw-semibold text-dark small">Payment Terms</label>
                            <input type="text" name="payment_terms" class="form-control px-3 py-2" id="payment_terms" placeholder="e.g. 30 Days Credit" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="credit_limit" class="form-label fw-semibold text-dark small">Credit Limit</label>
                            <input type="number" step="0.01" name="credit_limit" class="form-control px-3 py-2" id="credit_limit" placeholder="0.00" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="opening_balance" class="form-label fw-semibold text-dark small">Opening Balance</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control px-3 py-2" id="opening_balance" placeholder="0.00" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-12 mb-1 mt-2 d-flex align-items-center">
                            <label class="form-label fw-semibold text-dark small mb-0 me-3">Status</label>
                            <input type="hidden" name="status" value="0">
                            <label class="switch mb-0">
                                <input type="checkbox" name="status" id="status" value="1" checked>
                                <span class="slider"></span>
                            </label>
                            <span class="ms-2 small text-muted" id="statusLabel">Active</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                    @canany(['suppliers.create', 'suppliers.edit'])
                        <button type="submit" class="btn btn-primary px-4 fw-bold save-btn" style="border-radius: 8px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Supplier
                        </button>
                    @endcanany
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/js/mycode.js') }}"></script>
<script>
    // Status Toggle Label
    $('#status').on('change', function() {
        if($(this).is(':checked')) {
            $('#statusLabel').text('Active').removeClass('text-danger').addClass('text-success');
        } else {
            $('#statusLabel').text('Inactive').removeClass('text-success').addClass('text-danger');
        }
    });

    $(document).on('submit', '.myform', function(e) {
        e.preventDefault();
        var formdata = new FormData(this);
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        $(this).find(':submit').attr('disabled', true);
        myAjax(url, formdata, method);
    });

    $(document).on('click', '.edit-btn', function() {
        var tr = $(this).closest("tr");
        
        var id = tr.find(".id").text().replace('#', '').trim();
        var name = tr.find(".name").text().trim();
        var company_name = tr.find(".company_name").text().trim();
        var contact_person = tr.find(".contact_person").text().trim();
        var phone = tr.find(".phone").text().trim();
        var email = tr.find(".email").text().trim();
        var address = tr.find(".address").text().trim();
        var ntn_number = tr.find(".ntn_number").text().trim();
        var payment_terms = tr.find(".payment_terms").text().trim();
        var credit_limit = tr.find(".credit_limit").text().replace(/,/g, '').trim();
        var opening_balance = tr.find(".opening_balance").text().replace(/,/g, '').trim();
        var status = tr.find(".status").data('status');

        $('#id').val(id);
        $('#name').val(name);
        $('#company_name').val(company_name);
        $('#contact_person').val(contact_person);
        $('#phone').val(phone);
        $('#email').val(email);
        $('#address').val(address);
        $('#ntn_number').val(ntn_number);
        $('#payment_terms').val(payment_terms);
        $('#credit_limit').val(credit_limit);
        $('#opening_balance').val(opening_balance);
        
        if (status == 1 || status === undefined) {
            $('#status').prop('checked', true).trigger('change');
        } else {
            $('#status').prop('checked', false).trigger('change');
        }

        $('#modalTitleText').text('Edit Supplier');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#company_name').val('');
        $('#contact_person').val('');
        $('#phone').val('');
        $('#email').val('');
        $('#address').val('');
        $('#ntn_number').val('');
        $('#payment_terms').val('');
        $('#credit_limit').val('');
        $('#opening_balance').val('');
        $('#status').prop('checked', true).trigger('change');
        $('#modalTitleText').text('Add Supplier');
    });

    $(document).ready(function() {
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Suppliers:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
