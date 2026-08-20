@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .view-assigned-container {
            max-width: 1240px;
            margin: 0 auto;
        }

        /* Top Header Banner */
        .assigned-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 16px;
            padding: 22px 28px;
            color: #ffffff;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assigned-title-badge {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Quick Navigation Bar */
        .quick-nav-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .quick-nav-link {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .quick-nav-link:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        .quick-nav-link.active {
            background: #eef2ff;
            color: #4f46e5;
            border: 1px solid #c7d2fe;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.08);
        }

        /* Structure Overview Banner */
        .structure-overview-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 16px;
            padding: 20px 26px;
            color: #ffffff;
            margin-bottom: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
        }

        .structure-metric {
            padding: 0 14px;
            border-right: 1px solid rgba(255, 255, 255, 0.12);
        }

        .structure-metric:last-child {
            border-right: none;
        }

        .structure-metric-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .structure-metric-value {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        /* Table Card */
        .assigned-table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .assigned-table-header {
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .emp-table {
            width: 100%;
            margin-bottom: 0;
        }

        .emp-table th {
            background: #f8fafc;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            padding: 13px 18px;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
        }

        .emp-table td {
            padding: 13px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: #1e293b;
        }

        .emp-table tr:hover {
            background: #f8fafc;
        }

        .emp-table tr.inactive-row {
            background: #fafafa;
            opacity: 0.75;
        }

        .emp-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            margin-right: 10px;
        }

        .search-box-assigned {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px 7px 34px;
            font-size: 0.86rem;
            background: #ffffff;
            width: 250px;
            transition: all 0.2s;
        }

        .search-box-assigned:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .search-assigned-wrap {
            position: relative;
        }

        .search-assigned-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container view-assigned-container">

                <!-- Header Banner -->
                <div class="assigned-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="assigned-title-badge">
                            <i class="fa fa-users"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold" style="font-size: 1.35rem;">
                                Assigned Employees: {{ $salaryStructure->name }}
                            </h2>
                            <p class="mb-0 text-white-50" style="font-size: 0.85rem;">
                                View active assignments, custom overrides, and assignment history for this structure
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hr.salary-structure.assign-page', $salaryStructure->id) }}" class="btn btn-light btn-sm font-weight-bold px-3 py-2" style="border-radius: 9px;">
                            <i class="fa fa-user-plus text-primary me-1"></i> Assign Employees
                        </a>
                    </div>
                </div>

                <!-- Navigation Action Links in Header -->
                <div class="quick-nav-bar">
                    <a href="{{ route('hr.salary-structure.index') }}" class="quick-nav-link">
                        <i class="fa fa-th-large text-muted"></i> All Templates
                    </a>
                    <a href="{{ route('hr.salary-structure.assign-page', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-user-plus text-muted"></i> Assign Employees
                    </a>
                    <a href="{{ route('hr.salary-structure.view-assigned', $salaryStructure->id) }}" class="quick-nav-link active">
                        <i class="fa fa-users text-primary"></i> Assigned Employees ({{ $assignments->total() }})
                    </a>
                    <a href="{{ route('hr.salary-structure.individual-update-page', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-user-cog text-muted"></i> Custom Overrides
                    </a>
                    <a href="{{ route('hr.salary-structure.edit-template', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-pencil-alt text-muted"></i> Edit Template
                    </a>
                    <a href="{{ route('hr.payroll.index') }}" class="quick-nav-link ms-auto text-primary">
                        <i class="fa fa-file-invoice-dollar"></i> Payroll Dashboard <i class="fa fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <!-- Structure Financial Overview Card -->
                <div class="structure-overview-card">
                    <div class="row align-items-center">
                        <div class="col-md-3 structure-metric">
                            <div class="structure-metric-label">
                                <i class="fa fa-tag me-1 text-primary"></i> Structure Type
                            </div>
                            <div class="structure-metric-value text-truncate">
                                {{ $salaryStructure->name }}
                            </div>
                            <small class="text-white-50">
                                {{ ucfirst($salaryStructure->salary_type) }} {{ $salaryStructure->use_daily_wages ? '• Daily Wages' : '• Monthly' }}
                            </small>
                        </div>

                        <div class="col-md-2 structure-metric">
                            <div class="structure-metric-label">
                                <i class="fa fa-money-bill me-1"></i> Base / Daily Rate
                            </div>
                            <div class="structure-metric-value text-white">
                                Rs. {{ number_format($salaryStructure->use_daily_wages ? $salaryStructure->daily_wages : $salaryStructure->base_salary, 0) }}
                            </div>
                        </div>

                        <div class="col-md-2 structure-metric">
                            <div class="structure-metric-label text-success">
                                <i class="fa fa-plus-circle me-1"></i> Allowances
                            </div>
                            <div class="structure-metric-value text-success">
                                +Rs. {{ number_format($salaryStructure->total_allowances, 0) }}
                            </div>
                        </div>

                        <div class="col-md-2 structure-metric">
                            <div class="structure-metric-label text-danger">
                                <i class="fa fa-minus-circle me-1"></i> Deductions
                            </div>
                            <div class="structure-metric-value text-danger">
                                -Rs. {{ number_format($salaryStructure->total_deductions, 0) }}
                            </div>
                        </div>

                        <div class="col-md-3 structure-metric text-md-end">
                            <div class="structure-metric-label text-info">
                                <i class="fa fa-user-check me-1"></i> Active Assignments
                            </div>
                            <div class="badge bg-white text-dark px-3 py-2 font-weight-bold" style="font-size: 0.95rem; border-radius: 10px;">
                                <i class="fa fa-users text-primary me-1"></i> {{ $assignments->where('is_active', true)->count() }} Active ({{ $assignments->total() }} Total)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignments Table Card -->
                <div class="assigned-table-card">
                    <div class="assigned-table-header">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">
                                <i class="fa fa-history text-primary me-1"></i> Assignment Directory & History
                            </h5>
                            <span class="badge bg-light text-dark border px-2 py-1" id="assignmentCountBadge">
                                {{ $assignments->total() }} Records
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Live Search -->
                            <div class="search-assigned-wrap">
                                <i class="fa fa-search"></i>
                                <input type="text" id="assignmentSearch" class="search-box-assigned" placeholder="Search employee...">
                            </div>

                            <!-- Filter Pills -->
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="filter" id="filter_all" value="all" checked>
                                <label class="btn btn-outline-primary btn-sm px-3" for="filter_all">All</label>

                                <input type="radio" class="btn-check" name="filter" id="filter_active" value="active">
                                <label class="btn btn-outline-success btn-sm px-3" for="filter_active">Active</label>

                                <input type="radio" class="btn-check" name="filter" id="filter_ended" value="ended">
                                <label class="btn btn-outline-secondary btn-sm px-3" for="filter_ended">Ended</label>
                            </div>
                        </div>
                    </div>

                    @if ($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table emp-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Effective Period</th>
                                        <th>Structure Tier</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="assignmentsTable">
                                    @foreach ($assignments as $assignment)
                                        @php
                                            $emp = $assignment->employee;
                                            $initials = strtoupper(substr($emp->first_name ?? 'E', 0, 1) . substr($emp->last_name ?? '', 0, 1));
                                            $isCustom = ($assignment->salary_structure_id != $salaryStructure->id);
                                            $isActive = ($assignment->is_active && !$assignment->end_date);
                                        @endphp
                                        <tr class="assignment-row {{ $isActive ? '' : 'inactive-row' }}"
                                            data-status="{{ $isActive ? 'active' : 'ended' }}"
                                            data-name="{{ strtolower($emp->full_name ?? '') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="emp-avatar">{{ $initials ?: 'EM' }}</div>
                                                    <div>
                                                        <div class="font-weight-bold text-dark">{{ $emp->full_name ?? 'N/A' }}</div>
                                                        <small class="text-muted"><i class="fa fa-id-badge me-1"></i> {{ $emp->employee_code ?? 'EMP-' . $emp->id }}</small>
                                                        @if ($assignment->notes)
                                                            <div class="small text-muted fst-italic mt-1" style="font-size: 0.76rem;">
                                                                <i class="fa fa-comment text-primary me-1"></i> {{ $assignment->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="fa fa-building text-muted me-1"></i> {{ $emp->department->name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="fa fa-briefcase text-muted me-1"></i> {{ $emp->designation->name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <span class="text-success font-weight-bold">
                                                        <i class="fa fa-calendar-check me-1"></i> {{ $assignment->start_date ? $assignment->start_date->format('d M, Y') : 'N/A' }}
                                                    </span>
                                                    @if ($assignment->end_date)
                                                        <br>
                                                        <span class="text-danger">
                                                            <i class="fa fa-calendar-times me-1"></i> {{ $assignment->end_date->format('d M, Y') }}
                                                        </span>
                                                    @else
                                                        <br><span class="text-muted small">Active Ongoing</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($isCustom)
                                                    <span class="badge bg-warning text-dark px-2 py-1" title="Individual override active for this employee">
                                                        <i class="fa fa-user-edit me-1"></i> Custom Package
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark border px-2 py-1">
                                                        <i class="fa fa-clone text-muted me-1"></i> Standard Template
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $assignment->assignedBy->name ?? 'System Admin' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if ($isActive)
                                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 font-weight-bold">
                                                        <i class="fa fa-check-circle me-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary px-2 py-1 text-white">
                                                        <i class="fa fa-times-circle me-1"></i> Ended
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($isActive)
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <a href="{{ route('hr.salary-structure.edit-individual', $emp->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 7px;" title="Customize Employee Package">
                                                            <i class="fa fa-pencil-alt"></i> Customize
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger end-assignment-btn" style="border-radius: 7px;"
                                                            data-structure-id="{{ $salaryStructure->id }}"
                                                            data-employee-id="{{ $emp->id }}"
                                                            data-employee-name="{{ $emp->full_name }}" title="End Assignment">
                                                            <i class="fa fa-stop-circle"></i> End
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-4 py-3 border-top">
                            {{ $assignments->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fa fa-users fa-3x mb-3 text-secondary"></i>
                            <h5 class="font-weight-bold text-dark">No employees assigned to this structure yet.</h5>
                            <p class="text-muted small">You can assign this salary structure to single or multiple employees anytime.</p>
                            <a href="{{ route('hr.salary-structure.assign-page', $salaryStructure->id) }}" class="btn btn-primary px-4 py-2 font-weight-bold" style="border-radius: 10px;">
                                <i class="fa fa-user-plus me-1"></i> Assign Employees Now
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Live Search & Status Filter
            function applyFilters() {
                const search = $('#assignmentSearch').val().toLowerCase().trim();
                const filter = $('input[name="filter"]:checked').val();
                let count = 0;

                $('.assignment-row').each(function() {
                    const row = $(this);
                    const name = row.data('name') || '';
                    const status = row.data('status') || '';

                    const matchesStatus = (filter === 'all' || status === filter);
                    const matchesSearch = (!search || name.indexOf(search) !== -1 || row.text().toLowerCase().indexOf(search) !== -1);

                    if (matchesStatus && matchesSearch) {
                        row.show();
                        count++;
                    } else {
                        row.hide();
                    }
                });

                $('#assignmentCountBadge').text(count + ' Records');
            }

            $('#assignmentSearch').on('input', applyFilters);
            $('input[name="filter"]').on('change', applyFilters);

            // End Assignment Action
            $(document).on('click', '.end-assignment-btn', function() {
                const structureId = $(this).data('structure-id');
                const employeeId = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');

                Swal.fire({
                    title: 'End Structure Assignment?',
                    html: `Are you sure you want to end active salary structure for <strong>${employeeName}</strong>?<br><small class="text-muted">This employee will become unassigned until a new structure is assigned.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, End Assignment',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/hr/salary-structure/${structureId}/employee/${employeeId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                end_date: '{{ date('Y-m-d') }}'
                            },
                            success: function(response) {
                                Swal.fire('Assignment Ended!', response.success || 'Assignment ended successfully.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                let msg = 'Failed to end assignment.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    msg = xhr.responseJSON.error;
                                }
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
