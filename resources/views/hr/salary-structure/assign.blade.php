@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .assign-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .assign-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 16px;
            padding: 24px 30px;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assign-title-badge {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Structure Overview Card */
        .structure-overview-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 16px;
            padding: 22px 28px;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
        }

        .structure-metric {
            padding: 0 16px;
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
            margin-bottom: 4px;
            font-weight: 600;
        }

        .structure-metric-value {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        /* Modern Filter Bar */
        .smart-filter-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 22px;
            margin-bottom: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        }

        .filter-pill-btn {
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 18px;
            transition: all 0.2s;
        }

        .search-box-modern {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 9px 14px 9px 38px;
            font-size: 0.9rem;
            background: #f8fafc;
            width: 100%;
            transition: all 0.2s;
        }

        .search-box-modern:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Employee Table / Cards */
        .employee-list-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .employee-list-header {
            padding: 16px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
        }

        .emp-table td {
            padding: 14px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: #1e293b;
        }

        .emp-table tr:hover {
            background: #f8fafc;
        }

        .emp-table tr.already-assigned-row {
            background: #fffdf5;
        }

        .emp-table tr.has-other-row {
            background: #fdf8f6;
        }

        .emp-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            margin-right: 12px;
        }

        /* Checkboxes */
        .custom-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Sticky Action Card */
        .assignment-action-card {
            background: #ffffff;
            border: 2px solid #6366f1;
            border-radius: 16px;
            padding: 22px 28px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);
            margin-top: 24px;
        }

        .btn-assign-action {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
        }

        .btn-assign-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
            color: #ffffff;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container assign-container">

                <!-- Header Banner -->
                <div class="assign-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="assign-title-badge">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold" style="font-size: 1.45rem;">
                                Assign Salary Structure: {{ $salaryStructure->name }}
                            </h2>
                            <p class="mb-0 text-white-50" style="font-size: 0.88rem;">
                                Select and assign <span class="text-white font-weight-bold">"{{ $salaryStructure->name }}"</span> to active staff members
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('hr.salary-structure.index') }}" class="btn btn-light btn-sm font-weight-bold px-3 py-2" style="border-radius: 9px;">
                        <i class="fa fa-arrow-left me-1"></i> Back to Structures
                    </a>
                </div>

                <!-- Navigation Action Links in Header -->
                <div class="quick-nav-bar" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <a href="{{ route('hr.salary-structure.index') }}" class="quick-nav-link" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; color: #64748b; text-decoration: none;">
                        <i class="fa fa-th-large text-muted me-1"></i> All Templates
                    </a>
                    <a href="{{ route('hr.salary-structure.assign-page', $salaryStructure->id) }}" class="quick-nav-link active" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; color: #4f46e5; background: #eef2ff; border: 1px solid #c7d2fe; text-decoration: none;">
                        <i class="fa fa-user-plus text-primary me-1"></i> Assign Employees
                    </a>
                    <a href="{{ route('hr.salary-structure.view-assigned', $salaryStructure->id) }}" class="quick-nav-link" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; color: #64748b; text-decoration: none;">
                        <i class="fa fa-users text-muted me-1"></i> Assigned Employees ({{ $salaryStructure->assigned_count ?? 0 }})
                    </a>
                    <a href="{{ route('hr.salary-structure.individual-update-page', $salaryStructure->id) }}" class="quick-nav-link" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; color: #64748b; text-decoration: none;">
                        <i class="fa fa-user-cog text-muted me-1"></i> Custom Overrides
                    </a>
                    <a href="{{ route('hr.salary-structure.edit-template', $salaryStructure->id) }}" class="quick-nav-link" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; color: #64748b; text-decoration: none;">
                        <i class="fa fa-pencil-alt text-muted me-1"></i> Edit Template
                    </a>
                    <a href="{{ route('hr.payroll.index') }}" class="quick-nav-link ms-auto text-primary" style="padding: 7px 14px; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none;">
                        <i class="fa fa-file-invoice-dollar me-1"></i> Payroll Dashboard <i class="fa fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <!-- Structure Overview Card -->
                <div class="structure-overview-card">
                    <div class="row align-items-center">
                        <div class="col-md-3 structure-metric">
                            <div class="structure-metric-label">
                                <i class="fa fa-tag me-1 text-primary"></i> Structure Name
                            </div>
                            <div class="structure-metric-value text-truncate" title="{{ $salaryStructure->name }}">
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
                                Rs. {{ number_format($salaryStructure->use_daily_wages ? $salaryStructure->daily_wages : $salaryStructure->base_salary, 2) }}
                            </div>
                        </div>

                        <div class="col-md-2 structure-metric">
                            <div class="structure-metric-label text-success">
                                <i class="fa fa-plus-circle me-1"></i> Allowances
                            </div>
                            <div class="structure-metric-value text-success">
                                +Rs. {{ number_format($salaryStructure->total_allowances, 2) }}
                            </div>
                        </div>

                        <div class="col-md-2 structure-metric">
                            <div class="structure-metric-label text-danger">
                                <i class="fa fa-minus-circle me-1"></i> Deductions
                            </div>
                            <div class="structure-metric-value text-danger">
                                -Rs. {{ number_format($salaryStructure->total_deductions, 2) }}
                            </div>
                        </div>

                        <div class="col-md-3 structure-metric text-md-end">
                            <div class="structure-metric-label text-info">
                                <i class="fa fa-users me-1"></i> Currently Assigned
                            </div>
                            <div class="badge bg-white text-dark px-3 py-2 font-weight-bold" style="font-size: 0.95rem; border-radius: 10px;">
                                <i class="fa fa-user-check text-primary me-1"></i> {{ $salaryStructure->assigned_count ?? count($salaryStructure->assignedEmployees ?? []) }} Active
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Smart Filter & Live Search Bar -->
                <div class="smart-filter-bar">
                    <div class="row align-items-center g-3">
                        <!-- Filter By Type -->
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold text-muted small text-uppercase mb-2 d-block">Filter Scope</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="filter_type" id="filter_all" value="all" checked>
                                <label class="btn btn-outline-primary filter-pill-btn" for="filter_all">All ({{ count($employees) }})</label>

                                <input type="radio" class="btn-check" name="filter_type" id="filter_dept" value="department">
                                <label class="btn btn-outline-primary filter-pill-btn" for="filter_dept">By Department</label>

                                <input type="radio" class="btn-check" name="filter_type" id="filter_desig" value="designation">
                                <label class="btn btn-outline-primary filter-pill-btn" for="filter_desig">By Designation</label>
                            </div>
                        </div>

                        <!-- Department Selector -->
                        <div class="col-md-3" id="department_selector" style="display: none;">
                            <label class="form-label font-weight-bold text-muted small text-uppercase mb-2">Select Department</label>
                            <select class="form-select form-control" id="department_id" style="border-radius: 10px;">
                                <option value="">-- All Departments --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Designation Selector -->
                        <div class="col-md-3" id="designation_selector" style="display: none;">
                            <label class="form-label font-weight-bold text-muted small text-uppercase mb-2">Select Designation</label>
                            <select class="form-select form-control" id="designation_id" style="border-radius: 10px;">
                                <option value="">-- All Designations --</option>
                                @foreach ($designations as $desig)
                                    <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Instant Live Search -->
                        <div class="col-md-4 ms-auto">
                            <label class="form-label font-weight-bold text-muted small text-uppercase mb-2">Live Search</label>
                            <div class="search-wrapper">
                                <i class="fa fa-search"></i>
                                <input type="text" id="employeeSearchInput" class="search-box-modern" placeholder="Search by name, dept...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Table Card (Auto-Loaded) -->
                <div class="employee-list-card">
                    <div class="employee-list-header">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1rem;">
                                <i class="fa fa-users text-primary me-2"></i> Employee Directory
                            </h5>
                            <span class="badge bg-light text-dark border px-2 py-1" id="employeeCountBadge">
                                {{ count($employees) }} Employees
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold px-3" id="selectAllBtn" style="border-radius: 8px;">
                                <i class="fa fa-check-square me-1"></i> Select Available
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold px-3" id="clearAllBtn" style="border-radius: 8px;">
                                <i class="fa fa-square me-1"></i> Deselect All
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" id="employeesContainer">
                        <table class="table emp-table mb-0">
                            <thead>
                                <tr>
                                    <th width="45" class="text-center">
                                        <input type="checkbox" class="custom-check-input" id="selectAllCheckbox" title="Select All Available">
                                    </th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Current Assignment Status</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                @forelse ($employees as $emp)
                                    @php
                                        $initials = strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1));
                                        $isInactive = $emp->status !== 'active';
                                        $isAlready = $emp->is_already_assigned;
                                        $hasOther = $emp->has_other_structure;

                                        $rowClass = '';
                                        if ($isInactive) $rowClass = 'text-muted';
                                        elseif ($isAlready) $rowClass = 'already-assigned-row';
                                        elseif ($hasOther) $rowClass = 'has-other-row';
                                    @endphp
                                    <tr class="employee-row {{ $rowClass }}" 
                                        data-name="{{ strtolower($emp->full_name) }}"
                                        data-dept="{{ $emp->department_id ?? '' }}"
                                        data-desig="{{ $emp->designation_id ?? '' }}"
                                        data-status="{{ $emp->status }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="employee-checkbox custom-check-input" value="{{ $emp->id }}" 
                                                {{ $isInactive ? 'disabled' : '' }}>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="emp-avatar">{{ $initials ?: 'EM' }}</div>
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $emp->full_name }}</div>
                                                    <small class="text-muted"><i class="fa fa-id-badge me-1"></i> {{ $emp->employee_code ?? 'EMP-' . $emp->id }}</small>
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
                                            @if ($isInactive)
                                                <span class="badge bg-secondary px-2 py-1 text-uppercase">{{ $emp->status }}</span>
                                            @elseif ($isAlready)
                                                <span class="badge bg-warning text-dark px-2 py-1">
                                                    <i class="fa fa-check-circle me-1"></i> Already on This Structure
                                                </span>
                                            @elseif ($hasOther)
                                                <span class="badge bg-info text-white px-2 py-1" title="Will be transferred to this structure">
                                                    <i class="fa fa-exchange-alt me-1"></i> {{ $emp->other_structure_name }}
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1 font-weight-bold">
                                                    <i class="fa fa-check me-1"></i> Available
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa fa-users fa-3x mb-3 text-secondary"></i>
                                            <p class="mb-0 font-weight-bold">No active employees found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Assignment Form & Submission Panel -->
                <form id="assignmentForm" style="display: none;">
                    @csrf
                    <input type="hidden" name="salary_structure_id" value="{{ $salaryStructure->id }}">
                    <div id="selectedEmployeesInputs"></div>

                    <div class="assignment-action-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 font-weight-bold text-dark">
                                <i class="fa fa-calendar-check text-primary me-2"></i> Confirm Assignment Parameters
                            </h5>
                            <span class="badge bg-primary px-3 py-2 font-weight-bold" style="font-size: 0.95rem; border-radius: 8px;">
                                <i class="fa fa-user-check me-1"></i> <span id="selectedCount">0</span> Selected
                            </span>
                        </div>

                        <div class="row align-items-end g-3">
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold text-muted small text-uppercase">Effective Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control form-control-lg" style="border-radius: 10px; font-size: 0.9rem;" value="{{ date('Y-m-d') }}" required>
                                <small class="text-muted">Date from which this structure takes effect</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label font-weight-bold text-muted small text-uppercase">Notes / Reason (Optional)</label>
                                <input type="text" name="notes" class="form-control form-control-lg" style="border-radius: 10px; font-size: 0.9rem;" placeholder="e.g. Annual Promotion / Structure Assignment 2026">
                                <small class="text-muted">Optional reference note</small>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <button type="submit" class="btn btn-assign-action w-100" id="assignBtn">
                                    <i class="fa fa-check-circle me-1"></i> Assign Structure
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Live Client-side & AJAX filter handling
            function applyFilters() {
                const filterType = $('input[name="filter_type"]:checked').val();
                const departmentId = $('#department_id').val();
                const designationId = $('#designation_id').val();
                const searchQ = $('#employeeSearchInput').val().toLowerCase().trim();

                let visibleCount = 0;

                $('.employee-row').each(function() {
                    const row = $(this);
                    const name = row.data('name') || '';
                    const dept = String(row.data('dept') || '');
                    const desig = String(row.data('desig') || '');

                    let matchesFilter = true;

                    if (filterType === 'department' && departmentId) {
                        matchesFilter = (dept === String(departmentId));
                    } else if (filterType === 'designation' && designationId) {
                        matchesFilter = (desig === String(designationId));
                    }

                    let matchesSearch = true;
                    if (searchQ) {
                        matchesSearch = name.indexOf(searchQ) !== -1 || row.text().toLowerCase().indexOf(searchQ) !== -1;
                    }

                    if (matchesFilter && matchesSearch) {
                        row.show();
                        visibleCount++;
                    } else {
                        row.hide();
                    }
                });

                $('#employeeCountBadge').text(visibleCount + ' Employees');
            }

            // Radio toggle for filter mode
            $('input[name="filter_type"]').on('change', function() {
                const filterType = $(this).val();
                if (filterType === 'department') {
                    $('#department_selector').slideDown();
                    $('#designation_selector').slideUp();
                    $('#designation_id').val('');
                } else if (filterType === 'designation') {
                    $('#department_selector').slideUp();
                    $('#designation_selector').slideDown();
                    $('#department_id').val('');
                } else {
                    $('#department_selector').slideUp();
                    $('#designation_selector').slideUp();
                    $('#department_id').val('');
                    $('#designation_id').val('');
                }
                applyFilters();
            });

            $('#department_id, #designation_id').on('change', applyFilters);
            $('#employeeSearchInput').on('input', applyFilters);

            // Selection Logic
            function updateSelectedCount() {
                const checked = $('.employee-checkbox:checked');
                const count = checked.length;
                $('#selectedCount').text(count);

                if (count > 0) {
                    $('#assignmentForm').slideDown();

                    let html = '';
                    checked.each(function() {
                        html += `<input type="hidden" name="employee_ids[]" value="${$(this).val()}">`;
                    });
                    $('#selectedEmployeesInputs').html(html);
                } else {
                    $('#assignmentForm').slideUp();
                }
            }

            $(document).on('change', '.employee-checkbox', function() {
                updateSelectedCount();
            });

            // Master checkbox
            $('#selectAllCheckbox').on('change', function() {
                const isChecked = this.checked;
                $('.employee-row:visible .employee-checkbox:not(:disabled)').prop('checked', isChecked);
                updateSelectedCount();
            });

            $('#selectAllBtn').on('click', function() {
                $('.employee-row:visible .employee-checkbox:not(:disabled)').prop('checked', true);
                $('#selectAllCheckbox').prop('checked', true);
                updateSelectedCount();
            });

            $('#clearAllBtn').on('click', function() {
                $('.employee-checkbox').prop('checked', false);
                $('#selectAllCheckbox').prop('checked', false);
                updateSelectedCount();
            });

            // Form Submit with confirmation
            $('#assignmentForm').on('submit', function(e) {
                e.preventDefault();

                const count = $('.employee-checkbox:checked').length;
                if (count === 0) {
                    Swal.fire('No Employees Selected', 'Please select at least one employee from the directory.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Confirm Assignment',
                    html: `Assign <strong>"{{ $salaryStructure->name }}"</strong> to <strong>${count}</strong> employee(s)?<br><small class="text-muted">Previous active salary structures for these employees will be superseded automatically.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Assign Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performAssignment();
                    }
                });
            });

            function performAssignment() {
                const btn = $('#assignBtn');
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Assigning...');

                $.ajax({
                    url: '{{ route('hr.salary-structure.assign', $salaryStructure->id) }}',
                    method: 'POST',
                    data: $('#assignmentForm').serialize(),
                    success: function(response) {
                        let message = response.success || 'Salary structure assigned successfully!';
                        if (response.skipped && response.skipped.length > 0) {
                            message += '<br><br><strong>Skipped Employees:</strong><ul class="text-start mt-2">';
                            response.skipped.forEach(skip => {
                                message += `<li>${skip.name}: ${skip.reason}</li>`;
                            });
                            message += '</ul>';
                        }

                        Swal.fire({
                            title: 'Successfully Assigned!',
                            html: message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = response.redirect || '{{ route('hr.salary-structure.index') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = 'Assignment failed. Please check your connection or permissions.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                        btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Assign Structure');
                    }
                });
            }

            // Initial check
            updateSelectedCount();
        });
    </script>
@endpush
