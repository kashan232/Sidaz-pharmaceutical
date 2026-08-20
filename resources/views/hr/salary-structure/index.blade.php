@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .structure-card-modern {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        }

        .structure-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.12);
            border-color: #c7d2fe;
        }

        .structure-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .structure-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
            line-height: 1.3;
        }

        .structure-badge {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .salary-amount-box {
            text-align: right;
        }

        .salary-amount-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #4f46e5;
            margin: 0;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .salary-amount-label {
            color: #94a3b8;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .structure-details {
            flex: 1;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            border: 1px solid #f1f5f9;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.86rem;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-weight: 700;
            color: #1e293b;
        }

        .assigned-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: #4338ca;
            background: #eef2ff;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 4px;
        }

        .card-actions {
            margin-top: auto;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }

        .btn-assign-main {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 10px;
            padding: 10px 14px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.9rem;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-assign-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
        }

        .secondary-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            flex: 1;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 9px;
            border-radius: 8px;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            background-color: #ffffff;
            color: #4f46e5;
            border-color: #c7d2fe;
            transform: translateY(-1px);
        }

        .action-btn.delete:hover {
            background-color: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
        }

        /* Filter Tab Pills */
        .filter-tab-pill {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #64748b;
            background: #f1f5f9;
            transition: all 0.2s;
            border: 1px solid transparent;
            user-select: none;
        }

        .filter-tab-pill.active {
            color: #4f46e5;
            background: #eef2ff;
            border-color: #c7d2fe;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.1);
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="page-title"><i class="fa fa-money-bill-wave"></i> Salary Structures</h1>
                        <p class="page-subtitle">Create, configure and manage compensation templates and assign them to employees</p>
                    </div>
                    @if ($canCreate || $canEdit)
                        <button class="btn btn-create" onclick="createNewStructure()">
                            <i class="fa fa-plus me-1"></i> Create Structure
                        </button>
                    @endif
                </div>

                <!-- Stats Row -->
                @php
                    $totalStructures = \App\Models\Hr\SalaryStructure::whereNull('parent_structure_id')->whereNull('employee_id')->count();
                    $totalAssignments = \App\Models\Hr\EmployeeSalaryStructure::where('is_active', true)
                        ->distinct('employee_id')
                        ->count();
                    $unassignedEmployees = \App\Models\Hr\Employee::where('status', 'active')
                        ->whereDoesntHave('activeSalaryStructure')
                        ->count();
                @endphp
                <div class="stats-row">
                    <div class="stat-card primary">
                        <div class="stat-icon"><i class="fa fa-file-invoice-dollar"></i></div>
                        <div class="stat-value">{{ $totalStructures }}</div>
                        <div class="stat-label">Total Templates</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-icon"><i class="fa fa-users"></i></div>
                        <div class="stat-value">{{ $totalAssignments }}</div>
                        <div class="stat-label">Assigned Employees</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-icon"><i class="fa fa-user-times"></i></div>
                        <div class="stat-value">{{ $unassignedEmployees }}</div>
                        <div class="stat-label">Unassigned Employees</div>
                    </div>
                </div>

                <!-- Salary Structures Grid Card -->
                <div class="hr-card">
                    <div class="hr-header">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="search-box">
                                <i class="fa fa-search"></i>
                                <input type="search" id="structureSearch" placeholder="Search structures by name...">
                            </div>

                            <!-- Filter Tabs -->
                            <div class="d-flex gap-2" id="filterTabs">
                                <span class="filter-tab-pill active" data-type="all">All Structures</span>
                                <span class="filter-tab-pill" data-type="monthly">Monthly Salary</span>
                                <span class="filter-tab-pill" data-type="daily">Daily Wages</span>
                                <span class="filter-tab-pill" data-type="commission">Commission</span>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2 font-weight-bold" id="structureCount">
                            {{ $structures->total() }} Templates
                        </span>
                    </div>

                    <div class="hr-grid p-4" id="structuresGrid">
                        @forelse($structures as $structure)
                            @php
                                $childCount = $structure->children->sum('assigned_count');
                                $totalAssigned = $structure->assigned_count + $childCount;

                                // Determine clean structure classification
                                $isMonthly = (!$structure->use_daily_wages && $structure->salary_type === 'salary');
                                $isDailyOnly = ($structure->use_daily_wages && (float)$structure->base_salary <= 0);
                                $isMonthlyDaily = ($structure->use_daily_wages && (float)$structure->base_salary > 0);
                                $isCommissionOnly = ($structure->salary_type === 'commission');
                                $isMonthlyComm = ($structure->salary_type === 'both' || $structure->salary_type === 'monthly_commission');

                                $filterCategory = 'monthly';
                                if ($isDailyOnly || $isMonthlyDaily) $filterCategory = 'daily';
                                elseif ($isCommissionOnly || $isMonthlyComm) $filterCategory = 'commission';
                            @endphp

                            <div class="structure-card-modern"
                                data-name="{{ strtolower($structure->name ?? $structure->salary_type) }}"
                                data-type="{{ $filterCategory }}">

                                <div class="structure-card-header">
                                    <div style="flex: 1; padding-right: 12px;">
                                        <h3 class="structure-title">
                                            {{ $structure->name }}
                                        </h3>
                                        <div>
                                            @if ($isMonthly)
                                                <span class="structure-badge" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">
                                                    <i class="fa fa-calendar-alt"></i> Monthly Salary
                                                </span>
                                            @elseif ($isDailyOnly)
                                                <span class="structure-badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                                    <i class="fa fa-calendar-day"></i> Daily Wages
                                                </span>
                                            @elseif ($isMonthlyDaily)
                                                <span class="structure-badge" style="background: #fdf4ff; color: #86198f; border: 1px solid #f5d0fe;">
                                                    <i class="fa fa-layer-group"></i> Monthly + Daily
                                                </span>
                                            @elseif ($isCommissionOnly)
                                                <span class="structure-badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                                                    <i class="fa fa-chart-line"></i> Commission Only
                                                </span>
                                            @elseif ($isMonthlyComm)
                                                <span class="structure-badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                                    <i class="fa fa-percent"></i> Monthly + Comm.
                                                </span>
                                            @endif
                                        </div>
                                        <div class="assigned-pill">
                                            <i class="fa fa-user-check"></i>
                                            <span><strong>{{ $totalAssigned }}</strong> Assigned</span>
                                        </div>
                                    </div>

                                    <div class="salary-amount-box">
                                        @if ($isDailyOnly)
                                            <p class="salary-amount-value" style="color: #059669;">
                                                Rs. {{ number_format($structure->daily_wages, 0) }}
                                            </p>
                                            <p class="salary-amount-label">Per Day Rate</p>
                                        @elseif ($isCommissionOnly)
                                            <p class="salary-amount-value" style="color: #d97706;">
                                                {{ $structure->commission_percentage ?? 0 }}%
                                            </p>
                                            <p class="salary-amount-label">Commission</p>
                                        @else
                                            <p class="salary-amount-value">
                                                Rs. {{ number_format($structure->base_salary, 0) }}
                                            </p>
                                            <p class="salary-amount-label">Base Salary</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="structure-details">
                                    @if ($isMonthly || $isMonthlyComm)
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-gift text-success"></i> Allowances</span>
                                            <span class="detail-value text-success">+ Rs. {{ number_format($structure->total_allowances, 0) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-minus-circle text-danger"></i> Deductions</span>
                                            <span class="detail-value text-danger">- Rs. {{ number_format($structure->total_deductions, 0) }}</span>
                                        </div>
                                        <div class="detail-row pt-1 border-top">
                                            <span class="detail-label font-weight-bold text-dark"><i class="fa fa-wallet text-primary"></i> Net Package</span>
                                            <span class="detail-value text-primary font-weight-bold" style="font-size: 0.95rem;">
                                                Rs. {{ number_format($structure->base_salary + $structure->total_allowances - $structure->total_deductions, 0) }}
                                            </span>
                                        </div>
                                    @elseif ($isMonthlyDaily)
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-calendar-day text-success"></i> Daily Rate</span>
                                            <span class="detail-value">Rs. {{ number_format($structure->daily_wages, 0) }} / day</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-gift text-success"></i> Allowances</span>
                                            <span class="detail-value text-success">+ Rs. {{ number_format($structure->total_allowances, 0) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-minus-circle text-danger"></i> Deductions</span>
                                            <span class="detail-value text-danger">- Rs. {{ number_format($structure->total_deductions, 0) }}</span>
                                        </div>
                                    @elseif ($isDailyOnly)
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-clock text-secondary"></i> Penalty Rules</span>
                                            <span class="detail-value">
                                                {{ count($structure->attendance_deduction_policy['late_rules'] ?? []) }} Late /
                                                {{ count($structure->attendance_deduction_policy['early_rules'] ?? []) }} Early
                                            </span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-exchange-alt text-muted"></i> Carry Forward</span>
                                            <span class="detail-value">{{ $structure->carry_forward_deductions ? 'Yes' : 'No' }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-umbrella-beach text-info"></i> Leave Salary</span>
                                            <span class="detail-value">Rs. {{ number_format($structure->leave_salary_per_day ?? 0, 0) }}/day</span>
                                        </div>
                                    @elseif ($isCommissionOnly)
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-bullseye text-danger"></i> Monthly Target</span>
                                            <span class="detail-value">Rs. {{ number_format($structure->sales_target ?? 0, 0) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-layer-group text-warning"></i> Tier Ranges</span>
                                            <span class="detail-value">{{ count($structure->commission_tiers ?? []) }} Tiers</span>
                                        </div>
                                    @endif

                                    @if ($structure->commission_percentage && !$isCommissionOnly)
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="fa fa-percent text-warning"></i> Commission</span>
                                            <span class="detail-value">{{ $structure->commission_percentage }}%</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-actions">
                                    @if ($canEdit)
                                        <a href="{{ route('hr.salary-structure.assign-page', $structure->id) }}"
                                            class="btn-assign-main" title="Assign to Employees">
                                            <i class="fa fa-user-plus"></i> Assign Employees
                                        </a>
                                    @endif

                                    <div class="secondary-actions">
                                        @if ($canView || $canEdit)
                                            <a href="{{ route('hr.salary-structure.view-assigned', $structure->id) }}"
                                                class="action-btn" title="View Assigned Employees">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        @if ($canEdit)
                                            <a href="{{ route('hr.salary-structure.edit-template', $structure->id) }}"
                                                class="action-btn" title="Edit Template">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{ route('hr.salary-structure.individual-update-page', $structure->id) }}"
                                                class="action-btn" title="Update Individual Employees">
                                                <i class="fa fa-user-cog"></i>
                                            </a>
                                        @endif

                                        @if ($canDelete)
                                            <a href="javascript:void(0);" onclick="deleteStructure({{ $structure->id }})"
                                                class="action-btn delete" title="Delete Template">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state text-center py-5">
                                    <i class="fa fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <p class="font-weight-bold text-dark">No salary structures created yet.</p>
                                    @if ($canCreate || $canEdit)
                                        <button class="btn btn-create mt-2" onclick="createNewStructure()">
                                            <i class="fa fa-plus me-1"></i> Create First Structure
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($structures->total() > 0)
                        <div class="px-4 py-3 border-top">
                            {{ $structures->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function createNewStructure() {
            window.location.href = "{{ route('hr.salary-structure.create') }}";
        }

        function deleteStructure(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete this structure template.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('hr.salary-structure.destroy-template', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.success, 'success')
                                    .then(() => location.reload());
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Something went wrong!';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                msg = xhr.responseJSON.error;
                            }
                            Swal.fire('Error!', msg, 'error');
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            // Live Search & Tab Filter
            function filterCards() {
                var q = $('#structureSearch').val().toLowerCase().trim();
                var activeTab = $('.filter-tab-pill.active').data('type');
                var visibleCount = 0;

                $('.structure-card-modern').each(function() {
                    var name = $(this).data('name') || '';
                    var cardType = $(this).data('type') || '';

                    var matchesSearch = (name.indexOf(q) !== -1 || $(this).text().toLowerCase().indexOf(q) !== -1);
                    var matchesTab = (activeTab === 'all' || cardType === activeTab);

                    if (matchesSearch && matchesTab) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                $('#structureCount').text(visibleCount + ' Templates');
            }

            $('#structureSearch').on('input', filterCards);

            $('.filter-tab-pill').on('click', function() {
                $('.filter-tab-pill').removeClass('active');
                $(this).addClass('active');
                filterCards();
            });
        });
    </script>
@endpush
