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

    .dept-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .dept-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .dept-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .dept-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Stat Cards */
    .dept-stat-card {
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
    .dept-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #e0f2fe; /* Light Blue for Manufacturing */
        color: #0284c7; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .dept-stat-icon.active {
        background: #dcfce7;
        color: #16a34a;
    }
    .dept-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .dept-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-dept-primary {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-dept-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
    }

    /* Card & Table */
    .dept-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .dept-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dept-table-wrap {
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
    .dept-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }
    .dept-code-badge {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
        letter-spacing: 0.5px;
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
        border-color: #0284c7;
        box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.15);
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

    /* Mobile Cards View */
    .mobile-dept-cards {
        display: none;
        padding: 14px;
        flex-direction: column;
        gap: 12px;
    }
    .dept-mcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .dept-mcard-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .dept-mcard-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
    }
    .dept-mcard-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .dept-card-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 16px;
        }
        .btn-dept-primary {
            width: 100%;
            justify-content: center;
            height: 42px;
        }
        .dept-table-wrap {
            display: none !important;
        }
        .mobile-dept-cards {
            display: flex;
        }
    }
</style>

<div class="dept-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="dept-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="dept-title"><i class="fas fa-industry text-primary me-2" style="color: #0284c7 !important;"></i>Department Management</h3>
            <div class="dept-sub">Manage manufacturing departments, raw materials, and production units</div>
        </div>
        @can('departments.create')
            <button type="button" class="btn-dept-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Add Department
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="dept-stat-card">
                <div class="dept-stat-icon"><i class="fas fa-building"></i></div>
                <div>
                    <div class="dept-stat-val">{{ count($departments) }}</div>
                    <div class="dept-stat-lbl">Total Departments</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="dept-stat-card">
                <div class="dept-stat-icon active"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="dept-stat-val">{{ $departments->where('status', 1)->count() }}</div>
                    <div class="dept-stat-lbl">Active Departments</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="dept-card">
        <div class="dept-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Departments</div>
            <div class="text-muted small">Showing {{ count($departments) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="dept-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 90px;">Id</th>
                        <th class="text-start">Department Code</th>
                        <th class="text-start">Department Name</th>
                        <th class="text-start">Description</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $dept)
                        <tr>
                            <td class="text-center id"><span class="dept-id-badge">#{{ $dept->id }}</span></td>
                            <td class="text-start code"><span class="dept-code-badge">{{ $dept->code }}</span></td>
                            <td class="text-start name fw-semibold text-dark">{{ $dept->name }}</td>
                            <td class="text-start description text-muted small">{{ Str::limit($dept->description, 50) }}</td>
                            <td class="text-center status" data-status="{{ $dept->status }}">
                                @if($dept->status)
                                    <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('departments.store'),
                                    'deleteRoute' => route('departments.delete', $dept->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'departments.edit',
                                        'delete' => 'departments.delete',
                                    ],
                                    'deleteMsg' => 'Are you sure you want to delete this department?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View (< 768px) --}}
        <div class="mobile-dept-cards">
            @foreach ($departments as $dept)
                <div class="dept-mcard">
                    <div class="dept-mcard-hdr">
                        <span class="dept-id-badge">#{{ $dept->id }}</span>
                        @if($dept->status)
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </div>
                    <div class="dept-mcard-title mb-1">{{ $dept->name }}</div>
                    <div class="text-muted small mb-2"><span class="dept-code-badge">{{ $dept->code }}</span></div>
                    <div class="dept-mcard-actions">
                        @include('admin_panel.partials.action_buttons', [
                            'editRoute' => route('departments.store'),
                            'deleteRoute' => route('departments.delete', $dept->id),
                            'editIsLink' => false,
                            'permissions' => [
                                'edit' => 'departments.edit',
                                'delete' => 'departments.delete',
                            ],
                            'deleteMsg' => 'Are you sure you want to delete this department?',
                        ])
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Add/Edit Department Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-industry text-primary me-2" style="color: #0284c7 !important;"></i><span id="modalTitleText">Add Department</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold text-dark small">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="e.g. Syrup Production" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-semibold text-dark small">Department Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control px-3 py-2" id="code" placeholder="e.g. SYRUP" required style="border-radius: 10px; border: 1.5px solid #cbd5e1; text-transform: uppercase;" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold text-dark small">Description</label>
                            <textarea name="description" class="form-control px-3 py-2" id="description" rows="3" placeholder="Optional details..." style="border-radius: 10px; border: 1.5px solid #cbd5e1;"></textarea>
                        </div>
                        <div class="col-md-12 mb-1 d-flex align-items-center">
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
                    @canany(['departments.create', 'departments.edit'])
                        <button type="submit" class="btn btn-primary px-4 fw-bold save-btn" style="border-radius: 8px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Department
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

    // Fix ARIA focus warning on modal close
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
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
        var tr = $(this).closest("tr, .dept-mcard");
        var id = tr.find(".id, .dept-id-badge").text().replace('#', '').trim();
        var name = tr.find(".name, .dept-mcard-title").text().trim();
        var code = tr.find(".code, .dept-code-badge").text().trim();
        var description = tr.find(".description").text().trim();
        var status = tr.find(".status").data('status');

        $('#id').val(id);
        $('#name').val(name);
        $('#code').val(code);
        $('#description').val(description);
        
        if (status == 1 || status === undefined) {
            $('#status').prop('checked', true).trigger('change');
        } else {
            $('#status').prop('checked', false).trigger('change');
        }

        $('#modalTitleText').text('Edit Department');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#code').val('');
        $('#description').val('');
        $('#status').prop('checked', true).trigger('change');
        $('#modalTitleText').text('Add Department');
    });

    $(document).ready(function() {
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Department:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
