@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .compact-builder-container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .builder-topbar {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 14px;
            padding: 16px 24px;
            color: #ffffff;
            margin-bottom: 20px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .compact-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 18px;
            overflow: hidden;
        }

        .compact-card-header {
            padding: 12px 18px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .compact-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .compact-card-body {
            padding: 16px 18px;
        }

        .form-label-compact {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-control-compact,
        .form-select-compact {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 0.86rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control-compact:focus,
        .form-select-compact:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        /* Dynamic Item Rows */
        .item-row-compact {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 10px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .item-row-compact:hover {
            background: #ffffff;
            border-color: #cbd5e1;
        }

        /* Sticky Summary Panel */
        .sticky-summary-panel {
            position: sticky;
            top: 20px;
        }

        .summary-card-dark {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 14px;
            padding: 20px;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
            margin-bottom: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.85rem;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-net-box {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
            margin-top: 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .net-value-glow {
            color: #38bdf8;
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .btn-save-sticky {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
        }

        .btn-save-sticky:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.45);
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container compact-builder-container">

                <!-- Topbar Banner -->
                <div class="builder-topbar">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 1.5rem;"><i class="fa fa-calculator"></i></div>
                        <div>
                            <h2 class="mb-0 text-white font-weight-bold" style="font-size: 1.25rem;">
                                {{ isset($salaryStructure->id) ? 'Edit Salary Structure' : 'Create Salary Structure' }}
                            </h2>
                            <p class="mb-0 text-white-50 small">Configure compensation model, base wages, allowances & deductions</p>
                        </div>
                    </div>
                    <a href="{{ route('hr.salary-structure.index') }}" class="btn btn-light btn-sm font-weight-bold px-3 py-1" style="border-radius: 8px;">
                        <i class="fa fa-arrow-left me-1"></i> Back to Templates
                    </a>
                </div>

                @if ($readOnly ?? false)
                    <div class="alert alert-warning py-2 mb-3 border-0 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-eye me-1"></i> <strong>View Only Mode:</strong> You have view permission only. All fields are disabled.
                    </div>
                @endif

                @if (($hasAssignments ?? false) && isset($salaryStructure->id))
                    <div class="alert alert-warning py-2 mb-3 border-0 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-exclamation-triangle me-1"></i> <strong>Active Structure:</strong> This template is currently assigned to active employees.
                    </div>
                @endif

                <form id="salaryForm"
                    action="{{ isset($salaryStructure->id) ? route('hr.salary-structure.update-template', $salaryStructure->id) : route('hr.salary-structure.store') }}"
                    method="POST" data-ajax-validate="true">
                    @csrf
                    @if (isset($salaryStructure->id))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Left Column: Core Fields & Side-by-Side Tables -->
                        <div class="col-lg-8">

                            <!-- Section 1: Core Compensation -->
                            <div class="compact-card">
                                <div class="compact-card-header">
                                    <div class="compact-card-title">
                                        <i class="fa fa-sliders-h text-primary"></i>
                                        <span>1. Compensation Model & Core Rates</span>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary font-weight-bold px-2 py-1">General</span>
                                </div>
                                <div class="compact-card-body">
                                    <div class="row g-2">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label-compact">
                                                <i class="fa fa-file-signature text-primary"></i> Structure Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" class="form-control form-control-compact font-weight-bold" required
                                                placeholder="e.g. Executive Package, Production Worker"
                                                value="{{ $salaryStructure->name ?? '' }}"
                                                {{ $readOnly ?? false ? 'disabled' : '' }}>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label class="form-label-compact">
                                                <i class="fa fa-layer-group text-primary"></i> Compensation Model <span class="text-danger">*</span>
                                            </label>
                                            @php
                                                $uiType = 'monthly';
                                                if (isset($salaryStructure->id)) {
                                                    if ($salaryStructure->salary_type === 'commission') {
                                                        $uiType = 'commission';
                                                    } elseif ($salaryStructure->salary_type === 'both' || $salaryStructure->salary_type === 'monthly_commission') {
                                                        $uiType = 'monthly_commission';
                                                    } elseif ($salaryStructure->use_daily_wages) {
                                                        $uiType = ($salaryStructure->base_salary > 0) ? 'monthly_daily' : 'daily';
                                                    }
                                                }
                                            @endphp
                                            <select id="ui_structure_type" class="form-select form-select-compact" required
                                                {{ ($readOnly ?? false) || ($hasAssignments ?? false) ? 'disabled' : '' }}>
                                                <option value="monthly" {{ $uiType == 'monthly' ? 'selected' : '' }}>Monthly Fixed Salary Only</option>
                                                <option value="daily" {{ $uiType == 'daily' ? 'selected' : '' }}>Daily Wages Only</option>
                                                <option value="monthly_daily" {{ $uiType == 'monthly_daily' ? 'selected' : '' }}>Monthly Salary + Daily Wages</option>
                                                <option value="commission" {{ $uiType == 'commission' ? 'selected' : '' }}>Commission Only</option>
                                                <option value="monthly_commission" {{ $uiType == 'monthly_commission' ? 'selected' : '' }}>Monthly Salary + Commission</option>
                                            </select>

                                            <!-- Hidden Backend Mappings -->
                                            <input type="hidden" name="salary_type" id="salary_type" value="{{ $salaryStructure->salary_type ?? 'salary' }}">
                                            <input type="hidden" name="use_daily_wages" id="use_daily_wages_hidden" value="{{ $salaryStructure->use_daily_wages ? '1' : '0' }}">
                                        </div>

                                        <!-- Monthly Base Salary -->
                                        <div class="col-md-6 mb-2" id="base_salary_container">
                                            <label class="form-label-compact">
                                                <i class="fa fa-money-bill-wave text-success"></i> Monthly Base Salary (PKR)
                                            </label>
                                            <input type="number" step="0.01" name="base_salary" id="base_salary"
                                                class="form-control form-control-compact font-weight-bold"
                                                value="{{ $salaryStructure->base_salary ?? 0 }}"
                                                {{ $readOnly ?? false ? 'disabled' : '' }}>
                                        </div>

                                        <!-- Daily Wage Rate -->
                                        <div class="col-md-6 mb-2" id="daily_wages_container" style="display: none;">
                                            <label class="form-label-compact">
                                                <i class="fa fa-calendar-day text-success"></i> Daily Wage Rate (PKR)
                                            </label>
                                            <div class="form-check form-switch d-none">
                                                <input class="form-check-input" type="checkbox" id="use_daily_wages" value="1">
                                            </div>
                                            <input type="number" step="0.01" name="daily_wages" id="daily_wages"
                                                class="form-control form-control-compact font-weight-bold"
                                                value="{{ $salaryStructure->daily_wages ?? '' }}" placeholder="Rate per day"
                                                {{ ($readOnly ?? false) || !($salaryStructure->use_daily_wages ?? false) ? 'disabled' : '' }}>
                                        </div>

                                        <!-- Leave Salary Per Day -->
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label-compact">
                                                <i class="fa fa-umbrella-beach text-info"></i> Leave Salary Per Day (PKR)
                                            </label>
                                            <input type="number" step="0.01" name="leave_salary_per_day" class="form-control form-control-compact"
                                                value="{{ $salaryStructure->leave_salary_per_day ?? '' }}" placeholder="0.00"
                                                {{ $readOnly ?? false ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Side-by-Side Allowances & Deductions -->
                            <div class="row g-2">
                                <!-- Allowances (Col 6) -->
                                <div class="col-md-6">
                                    <div class="compact-card h-100">
                                        <div class="compact-card-header py-2" style="background: #f0fdf4;">
                                            <div class="compact-card-title text-success">
                                                <i class="fa fa-gift text-success"></i>
                                                <span>Allowances</span>
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm py-0 px-2" id="addAllowance" style="font-size: 0.75rem; border-radius: 6px;">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </div>
                                        <div class="compact-card-body p-2">
                                            <div id="allowances_container">
                                                @if ($salaryStructure->allowances)
                                                    @foreach ($salaryStructure->allowances as $index => $allowance)
                                                        <div class="item-row-compact allowance-row">
                                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input allowance-active" type="checkbox"
                                                                        name="allowances[{{ $index }}][is_active]" value="1"
                                                                        {{ !isset($allowance['is_active']) || $allowance['is_active'] ? 'checked' : '' }}
                                                                        {{ $readOnly ?? false ? 'disabled' : '' }}>
                                                                </div>
                                                                <input type="text" name="allowances[{{ $index }}][name]" class="form-control form-control-compact py-1"
                                                                    placeholder="Allowance Name" value="{{ $allowance['name'] ?? '' }}">
                                                                <button type="button" class="btn btn-outline-danger btn-sm p-1 remove-row" style="border-radius: 6px; line-height: 1;">
                                                                    <i class="fa fa-times" style="font-size: 0.75rem;"></i>
                                                                </button>
                                                            </div>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text py-0" style="font-size: 0.75rem;">PKR</span>
                                                                <input type="number" step="0.01" name="allowances[{{ $index }}][amount]"
                                                                    class="form-control form-control-compact allowance-amount py-1 font-weight-bold"
                                                                    placeholder="Amount" value="{{ $allowance['amount'] ?? '' }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div id="no_allowances_message" class="text-center text-muted py-3"
                                                style="{{ $salaryStructure->allowances && count($salaryStructure->allowances) > 0 ? 'display:none;' : '' }}">
                                                <small><i class="fa fa-info-circle"></i> No allowances added</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deductions (Col 6) -->
                                <div class="col-md-6">
                                    <div class="compact-card h-100">
                                        <div class="compact-card-header py-2" style="background: #fef2f2;">
                                            <div class="compact-card-title text-danger">
                                                <i class="fa fa-minus-circle text-danger"></i>
                                                <span>Deductions</span>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm py-0 px-2" id="addDeduction" style="font-size: 0.75rem; border-radius: 6px;">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </div>
                                        <div class="compact-card-body p-2">
                                            <div id="deductions_container">
                                                @if ($salaryStructure->deductions)
                                                    @foreach ($salaryStructure->deductions as $index => $deduction)
                                                        <div class="item-row-compact deduction-row">
                                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input deduction-active" type="checkbox"
                                                                        name="deductions[{{ $index }}][is_active]" value="1"
                                                                        {{ !isset($deduction['is_active']) || $deduction['is_active'] ? 'checked' : '' }}
                                                                        {{ $readOnly ?? false ? 'disabled' : '' }}>
                                                                </div>
                                                                <input type="text" name="deductions[{{ $index }}][name]" class="form-control form-control-compact py-1"
                                                                    placeholder="Deduction Name" value="{{ $deduction['name'] ?? '' }}">
                                                                <button type="button" class="btn btn-outline-danger btn-sm p-1 remove-row" style="border-radius: 6px; line-height: 1;">
                                                                    <i class="fa fa-times" style="font-size: 0.75rem;"></i>
                                                                </button>
                                                            </div>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text py-0" style="font-size: 0.75rem;">PKR</span>
                                                                <input type="number" step="0.01" name="deductions[{{ $index }}][amount]"
                                                                    class="form-control form-control-compact deduction-amount py-1 font-weight-bold"
                                                                    placeholder="Amount" value="{{ $deduction['amount'] ?? '' }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div id="no_deductions_message" class="text-center text-muted py-3"
                                                style="{{ $salaryStructure->deductions && count($salaryStructure->deductions) > 0 ? 'display:none;' : '' }}">
                                                <small><i class="fa fa-info-circle"></i> No deductions added</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Commission Settings (Conditional) -->
                            <div class="compact-card mt-2" id="commission_section" style="display: none;">
                                <div class="compact-card-header" style="background: #fffbeb;">
                                    <div class="compact-card-title text-warning">
                                        <i class="fa fa-chart-line text-warning"></i>
                                        <span>Commission & Sales Targets</span>
                                    </div>
                                </div>
                                <div class="compact-card-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label-compact">Monthly Sales Target</label>
                                            <input type="number" step="0.01" name="sales_target" id="sales_target"
                                                class="form-control form-control-compact"
                                                value="{{ $salaryStructure->sales_target ?? '' }}" placeholder="PKR 50000">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-compact">Commission Model</label>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="commission_mode" id="mode_flat" value="flat"
                                                    {{ !$salaryStructure->commission_tiers || count($salaryStructure->commission_tiers ?? []) == 0 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-info btn-sm py-1 font-weight-bold" for="mode_flat">Flat %</label>

                                                <input type="radio" class="btn-check" name="commission_mode" id="mode_tiered" value="tiered"
                                                    {{ $salaryStructure->commission_tiers && count($salaryStructure->commission_tiers ?? []) > 0 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning btn-sm py-1 font-weight-bold" for="mode_tiered">Tiered</label>
                                            </div>
                                        </div>
                                        <div class="col-12" id="flat_commission_section" style="display: none;">
                                            <label class="form-label-compact">Flat Commission Percentage (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="commission_percentage"
                                                id="commission_percentage" class="form-control form-control-compact"
                                                value="{{ $salaryStructure->commission_percentage ?? '' }}" placeholder="e.g. 5">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Right Column: Sticky Live Calculation & Actions -->
                        <div class="col-lg-4">
                            <div class="sticky-summary-panel">

                                <!-- Live Financial Projection Card -->
                                <div class="summary-card-dark">
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
                                        <span class="text-uppercase text-white-50 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fa fa-calculator me-1"></i> Live Projection
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 0.7rem;">Live</span>
                                    </div>

                                    <div class="summary-row">
                                        <span class="text-white-50">Base / Wages</span>
                                        <span class="font-weight-bold text-white" id="summary_base">0.00</span>
                                    </div>

                                    <div class="summary-row">
                                        <span class="text-success"><i class="fa fa-plus-circle me-1"></i> Allowances</span>
                                        <span class="font-weight-bold text-success" id="summary_allowances">0.00</span>
                                    </div>

                                    <div class="summary-row">
                                        <span class="text-danger"><i class="fa fa-minus-circle me-1"></i> Deductions</span>
                                        <span class="font-weight-bold text-danger" id="summary_deductions">0.00</span>
                                    </div>

                                    <div class="summary-net-box">
                                        <small class="text-white-50 text-uppercase d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Estimated Net Salary</small>
                                        <div class="net-value-glow" id="summary_net">Rs. 0.00</div>
                                    </div>

                                    @if (!($readOnly ?? false))
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-save-sticky">
                                                <i class="fa fa-check-circle me-1"></i> Save Salary Structure
                                            </button>
                                            <a href="{{ route('hr.salary-structure.index') }}"
                                                class="btn btn-sm btn-outline-light w-100 mt-2 font-weight-bold" style="border-radius: 8px; opacity: 0.8;">
                                                Cancel
                                            </a>
                                        </div>
                                    @endif
                                </div>

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
            var allowanceIndex = {{ count($salaryStructure->allowances ?? []) }};
            var deductionIndex = {{ count($salaryStructure->deductions ?? []) }};
            var isReadOnly = {{ $readOnly ?? false ? 'true' : 'false' }};

            if (isReadOnly) {
                $('#salaryForm input, #salaryForm select, #salaryForm textarea').prop('disabled', true);
                $('#salaryForm .remove-row').hide();
                $('#addAllowance, #addDeduction').hide();
            }

            function updateStructureUI() {
                var type = $('#ui_structure_type').val();
                $('#base_salary_container').hide();
                $('#daily_wages_container').hide();
                $('#commission_section').slideUp();

                var salaryType = 'salary';
                var useDaily = '0';
                var isDailyChecked = false;

                if (type === 'monthly') {
                    salaryType = 'salary';
                    $('#base_salary_container').show();
                    useDaily = '0';
                } else if (type === 'daily') {
                    salaryType = 'salary';
                    $('#daily_wages_container').show();
                    useDaily = '1';
                    isDailyChecked = true;
                } else if (type === 'monthly_daily') {
                    salaryType = 'salary';
                    $('#base_salary_container').show();
                    $('#daily_wages_container').show();
                    useDaily = '1';
                    isDailyChecked = true;
                } else if (type === 'commission') {
                    salaryType = 'commission';
                    $('#commission_section').slideDown();
                } else if (type === 'monthly_commission') {
                    salaryType = 'both';
                    $('#base_salary_container').show();
                    $('#commission_section').slideDown();
                }

                $('#salary_type').val(salaryType);
                $('#use_daily_wages_hidden').val(useDaily);
                $('#use_daily_wages').prop('checked', isDailyChecked);
                recalculate();
            }

            $('#ui_structure_type').on('change', updateStructureUI);
            updateStructureUI();

            function updateCommissionModeUI() {
                var mode = $('input[name="commission_mode"]:checked').val();
                if (mode === 'flat') {
                    $('#flat_commission_section').slideDown();
                } else {
                    $('#flat_commission_section').slideUp();
                }
            }

            $('input[name="commission_mode"]').on('change', updateCommissionModeUI);
            updateCommissionModeUI();

            // Add Allowance
            $('#addAllowance').click(function() {
                var html = `
                    <div class="item-row-compact allowance-row">
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input allowance-active" type="checkbox"
                                    name="allowances[${allowanceIndex}][is_active]" value="1" checked>
                            </div>
                            <input type="text" name="allowances[${allowanceIndex}][name]" class="form-control form-control-compact py-1"
                                placeholder="Allowance Name" required>
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 remove-row" style="border-radius: 6px; line-height: 1;">
                                <i class="fa fa-times" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text py-0" style="font-size: 0.75rem;">PKR</span>
                            <input type="number" step="0.01" name="allowances[${allowanceIndex}][amount]"
                                class="form-control form-control-compact allowance-amount py-1 font-weight-bold"
                                placeholder="Amount" required>
                        </div>
                    </div>
                `;
                $('#allowances_container').append(html);
                $('#no_allowances_message').hide();
                allowanceIndex++;
                recalculate();
            });

            // Add Deduction
            $('#addDeduction').click(function() {
                var html = `
                    <div class="item-row-compact deduction-row">
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input deduction-active" type="checkbox"
                                    name="deductions[${deductionIndex}][is_active]" value="1" checked>
                            </div>
                            <input type="text" name="deductions[${deductionIndex}][name]" class="form-control form-control-compact py-1"
                                placeholder="Deduction Name" required>
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 remove-row" style="border-radius: 6px; line-height: 1;">
                                <i class="fa fa-times" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text py-0" style="font-size: 0.75rem;">PKR</span>
                            <input type="number" step="0.01" name="deductions[${deductionIndex}][amount]"
                                class="form-control form-control-compact deduction-amount py-1 font-weight-bold"
                                placeholder="Amount" required>
                        </div>
                    </div>
                `;
                $('#deductions_container').append(html);
                $('#no_deductions_message').hide();
                deductionIndex++;
                recalculate();
            });

            $(document).on('click', '.remove-row', function() {
                var isAllowance = $(this).closest('.allowance-row').length > 0;
                var isDeduction = $(this).closest('.deduction-row').length > 0;
                $(this).closest('.item-row-compact').remove();

                if (isAllowance && $('#allowances_container .allowance-row').length === 0) {
                    $('#no_allowances_message').show();
                }
                if (isDeduction && $('#deductions_container .deduction-row').length === 0) {
                    $('#no_deductions_message').show();
                }
                recalculate();
            });

            function recalculate() {
                var baseSalary = parseFloat($('#base_salary').val()) || 0;
                var dailyWages = parseFloat($('#daily_wages').val()) || 0;
                var useDaily = $('#ui_structure_type').val().includes('daily');

                var totalBase = baseSalary;
                if (useDaily) {
                    totalBase += (dailyWages * 30);
                }

                var totalAllowances = 0;
                $('.allowance-row').each(function() {
                    if ($(this).find('.allowance-active').is(':checked')) {
                        totalAllowances += parseFloat($(this).find('.allowance-amount').val()) || 0;
                    }
                });

                var totalDeductions = 0;
                $('.deduction-row').each(function() {
                    if ($(this).find('.deduction-active').is(':checked')) {
                        totalDeductions += parseFloat($(this).find('.deduction-amount').val()) || 0;
                    }
                });

                var net = totalBase + totalAllowances - totalDeductions;

                $('#summary_base').text('PKR ' + totalBase.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#summary_allowances').text('+PKR ' + totalAllowances.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#summary_deductions').text('-PKR ' + totalDeductions.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#summary_net').text('Rs. ' + net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }

            $(document).on('input change', '#base_salary, #daily_wages, .allowance-amount, .allowance-active, .deduction-amount, .deduction-active', function() {
                recalculate();
            });

            recalculate();
        });
    </script>
@endpush
