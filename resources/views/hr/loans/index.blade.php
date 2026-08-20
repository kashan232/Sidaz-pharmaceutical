@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .loans-container {
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Modal Styles */
        .modal-header-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            padding: 16px 22px;
        }

        .modal-content-modern {
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
            overflow: hidden;
        }

        .modal-footer-modern {
            padding: 14px 22px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-label-compact {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-control-compact,
        .form-select-compact {
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 0.88rem;
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

        /* Select2 Fixes for Bootstrap Modals */
        .select2-container--default .select2-selection--single {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 9px !important;
            height: 42px !important;
            padding: 6px 10px !important;
            background-color: #f8fafc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            line-height: 28px !important;
            font-size: 0.88rem !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-dropdown {
            border: 1.5px solid #6366f1 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            z-index: 1065 !important;
        }

        .calc-preview-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: #4338ca;
        }

        /* Card Action Buttons */
        .action-btn-pill {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: all 0.2s;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container loans-container">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title"><i class="fa fa-hand-holding-usd"></i> Loans & Staff Advances</h1>
                        <p class="page-subtitle">Manage employee loans, monthly salary deductions, and repayment histories</p>
                    </div>
                    @can('hr.loans.create')
                        <button type="button" class="btn btn-create btn-primary px-4 py-2 font-weight-bold"
                            data-toggle="modal" data-target="#addLoanModal"
                            data-bs-toggle="modal" data-bs-target="#addLoanModal">
                            <i class="fa fa-plus me-1"></i> New Loan Request
                        </button>
                    @endcan
                </div>

                <!-- Stats Row -->
                @php
                    $pendingCount = \App\Models\Hr\Loan::where('status', 'pending')->count();
                    $activeAmount = \App\Models\Hr\Loan::where('status', 'approved')->sum('amount') -
                        \App\Models\Hr\Loan::where('status', 'approved')->sum('paid_amount');
                    $paidCount = \App\Models\Hr\Loan::where('status', 'paid')->count();
                @endphp
                <div class="stats-row">
                    <div class="stat-card primary">
                        <div class="stat-icon"><i class="fa fa-file-invoice-dollar"></i></div>
                        <div class="stat-value">{{ $loans->total() }}</div>
                        <div class="stat-label">Total Loans</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-icon"><i class="fa fa-clock"></i></div>
                        <div class="stat-value">{{ $pendingCount }}</div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-icon"><i class="fa fa-money-bill-wave"></i></div>
                        <div class="stat-value">Rs. {{ number_format($activeAmount, 0) }}</div>
                        <div class="stat-label">Active Balance</div>
                    </div>
                    <div class="stat-card info">
                        <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="stat-value">{{ $paidCount }}</div>
                        <div class="stat-label">Fully Settled</div>
                    </div>
                </div>

                <!-- Loans Grid Card -->
                <div class="hr-card">
                    <div class="hr-header">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="search-box">
                                <i class="fa fa-search"></i>
                                <input type="search" id="loanSearch" placeholder="Search loans by employee name, status...">
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()" title="Refresh List" style="border-radius: 8px; padding: 8px 14px;">
                                <i class="fa fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2 font-weight-bold" id="loanCount">
                            {{ $loans->total() }} Records
                        </span>
                    </div>

                    <div class="hr-grid p-4" id="loanGrid">
                        @forelse($loans as $loan)
                            @php
                                $emp = $loan->employee;
                                $initials = strtoupper(substr($emp->first_name ?? 'E', 0, 1) . substr($emp->last_name ?? '', 0, 1));
                            @endphp
                            <div class="hr-item-card" data-id="{{ $loan->id }}"
                                data-employee="{{ strtolower($emp->full_name ?? '') }}"
                                data-designation="{{ strtolower($emp->designation->name ?? '') }}"
                                data-date="{{ $loan->created_at->format('d/m/Y') }}"
                                data-amount="{{ $loan->amount }}"
                                data-status="{{ $loan->status }}">

                                <div class="hr-item-header mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="hr-avatar" style="background: linear-gradient(135deg, #6366f1, #7c3aed); color: #fff; font-weight: 700;">
                                            {{ $initials ?: 'EM' }}
                                        </div>
                                        <div class="hr-item-info ms-2">
                                            <h4 class="hr-item-name mb-0 font-weight-bold" style="font-size: 1rem;">
                                                {{ $emp->full_name ?? 'N/A' }}
                                            </h4>
                                            <div class="text-muted small">
                                                {{ $emp->designation->name ?? 'Staff' }} • <i class="fa fa-calendar-alt text-primary"></i> {{ $loan->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dropdown Menu -->
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm border" type="button"
                                            data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 10px; min-width: 180px;">
                                            @if ($loan->status == 'pending')
                                                @can('hr.loans.approve')
                                                    <li>
                                                        <a class="dropdown-item text-success py-2 font-weight-bold" href="javascript:void(0)" onclick="approveLoan({{ $loan->id }})">
                                                            <i class="fa fa-check-circle me-2"></i> Approve Loan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger py-2 font-weight-bold" href="javascript:void(0)" onclick="rejectLoan({{ $loan->id }})">
                                                            <i class="fa fa-times-circle me-2"></i> Reject Loan
                                                        </a>
                                                    </li>
                                                @endcan
                                            @endif

                                            @if ($loan->status == 'approved')
                                                <li>
                                                    <a class="dropdown-item text-primary py-2 font-weight-bold" href="javascript:void(0)"
                                                        onclick="scheduleDeductionModal({{ $loan->id }}, {{ $loan->remaining_amount }})">
                                                        <i class="fa fa-calendar-plus me-2"></i> Schedule Deduction
                                                    </a>
                                                </li>
                                            @endif

                                            <li>
                                                <a class="dropdown-item text-dark py-2 font-weight-bold" href="javascript:void(0)" onclick="viewHistory({{ $loan->id }})">
                                                    <i class="fa fa-history me-2 text-info"></i> View History
                                                </a>
                                            </li>

                                            @can('hr.loans.delete')
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger py-2 font-weight-bold" href="javascript:void(0)" onclick="deleteLoan({{ $loan->id }})">
                                                        <i class="fa fa-trash-alt me-2"></i> Delete Loan Record
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>

                                <!-- Financial Numbers -->
                                <div class="bg-light p-3 rounded-3 my-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small">Total Loan:</span>
                                        <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">Rs. {{ number_format($loan->amount, 0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small">Monthly Deduction:</span>
                                        <span class="font-weight-bold text-primary">
                                            @if ($loan->installment_amount > 0)
                                                Rs. {{ number_format($loan->installment_amount, 0) }} <small class="text-muted">/mo</small>
                                            @else
                                                <span class="badge bg-secondary text-white">Manual Pay</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                                        <span class="text-muted small font-weight-bold">Remaining Balance:</span>
                                        <span class="font-weight-bold text-danger">Rs. {{ number_format($loan->remaining_amount, 0) }}</span>
                                    </div>
                                </div>

                                <!-- Status Tag -->
                                <div class="mt-auto">
                                    @if ($loan->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning w-100 py-2 font-weight-bold">
                                            <i class="fa fa-clock me-1"></i> Pending Approval
                                        </span>
                                    @elseif ($loan->status == 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success w-100 py-2 font-weight-bold">
                                            <i class="fa fa-check-circle me-1"></i> Active Loan
                                        </span>
                                    @elseif ($loan->status == 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger w-100 py-2 font-weight-bold">
                                            <i class="fa fa-times-circle me-1"></i> Rejected
                                        </span>
                                    @elseif ($loan->status == 'paid')
                                        <span class="badge bg-info-subtle text-info border border-info w-100 py-2 font-weight-bold">
                                            <i class="fa fa-calendar-check me-1"></i> Fully Settled
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5 text-muted">
                                    <i class="fa fa-hand-holding-usd fa-3x text-secondary mb-3"></i>
                                    <p class="font-weight-bold text-dark mb-1">No loan records found.</p>
                                    <p class="small text-muted mb-3">Create a new loan request to manage staff advances.</p>
                                    @can('hr.loans.create')
                                        <button class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 9px;"
                                            data-toggle="modal" data-target="#addLoanModal"
                                            data-bs-toggle="modal" data-bs-target="#addLoanModal">
                                            <i class="fa fa-plus me-1"></i> Create First Request
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($loans->total() > 0)
                        <div class="px-4 py-3 border-top">
                            {{ $loans->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- 1. Add Loan Request Modal -->
    <div class="modal fade" id="addLoanModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-gradient d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-weight-bold text-white mb-0">
                        <i class="fa fa-plus-circle me-2"></i> New Loan / Staff Advance Request
                    </h5>
                    <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('hr.loans.store') }}" method="POST" id="addLoanForm" data-ajax-validate="true">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label-compact">
                                <i class="fa fa-user text-primary"></i> Select Employee <span class="text-danger">*</span>
                            </label>
                            <select name="employee_id" class="form-select select2" required style="width: 100%;">
                                <option value="">-- Choose Employee --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->department->name ?? 'Staff' }} • {{ $emp->designation->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-compact">
                                    <i class="fa fa-money-bill-wave text-success"></i> Loan Amount (PKR) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" name="amount" id="modal_loan_amount" class="form-control form-control-compact font-weight-bold" required min="1" placeholder="e.g. 50000">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label-compact">
                                    <i class="fa fa-calendar-alt text-warning"></i> Monthly Installment (PKR)
                                </label>
                                <input type="number" step="0.01" name="installment_amount" id="modal_installment_amount" class="form-control form-control-compact font-weight-bold" min="0" value="0" placeholder="0 for Manual">
                                <small class="text-muted" style="font-size: 0.72rem;">Set 0 for lump sum or manual deduction</small>
                            </div>
                        </div>

                        <!-- Live Calculation Box -->
                        <div class="calc-preview-box" id="calc_preview_box" style="display: none;">
                            <span><i class="fa fa-calculator me-1"></i> Estimated Repayment Duration:</span>
                            <span class="font-weight-bold" id="calc_months_text">0 Months</span>
                        </div>

                        <div class="mb-2 mt-3">
                            <label class="form-label-compact">
                                <i class="fa fa-comment text-muted"></i> Reason / Approval Notes
                            </label>
                            <textarea name="reason" class="form-control form-control-compact" rows="3" placeholder="Enter purpose of loan or advance request..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold" style="border-radius: 8px;">
                            <i class="fa fa-paper-plane me-1"></i> Submit Loan Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Schedule Deduction Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-modern">
                <div class="modal-header bg-warning text-dark d-flex justify-content-between align-items-center" style="border-top-left-radius: 14px; border-top-right-radius: 14px; padding: 14px 20px;">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fa fa-calendar-plus me-2"></i> Schedule One-Time Deduction
                    </h5>
                    <button type="button" class="close text-dark border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('hr.loans.schedule') }}" method="POST" id="scheduleForm" data-ajax-validate="true">
                    @csrf
                    <input type="hidden" name="loan_id" id="schedule_loan_id">
                    <div class="modal-body p-4">
                        <div class="alert alert-warning py-2 mb-3 border-0 small" style="border-radius: 10px;">
                            <i class="fa fa-info-circle me-1"></i> Force a specific deduction amount from the employee's salary payroll for a selected month.
                        </div>

                        <div class="mb-3">
                            <label class="form-label-compact">Deduction Amount (PKR) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="schedule_amount" class="form-control form-control-compact font-weight-bold" required min="1">
                            <small class="text-muted">Maximum available balance: <span id="max_sched_amount" class="font-weight-bold text-danger">0</span> PKR</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-compact">Target Payroll Month <span class="text-danger">*</span></label>
                            <input type="month" name="month" class="form-control form-control-compact" required value="{{ date('Y-m') }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label-compact">Deduction Notes</label>
                            <input type="text" name="notes" class="form-control form-control-compact" placeholder="Optional notes for payroll payslip">
                        </div>
                    </div>

                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm px-4 font-weight-bold text-dark" style="border-radius: 8px;">
                            <i class="fa fa-calendar-check me-1"></i> Schedule Deduction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Loan History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-content-modern">
                <div class="modal-header bg-dark text-white d-flex justify-content-between align-items-center" style="border-top-left-radius: 14px; border-top-right-radius: 14px; padding: 16px 22px;">
                    <h5 class="modal-title font-weight-bold text-white mb-0">
                        <i class="fa fa-history me-2 text-info"></i> Loan Ledger & Payment History
                    </h5>
                    <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="p-4 bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h4 class="mb-0 font-weight-bold text-dark" id="hist_emp_name">Loading Employee...</h4>
                                <small class="text-muted" id="hist_emp_subtitle"></small>
                            </div>
                            <span class="badge bg-primary px-3 py-2" id="hist_status" style="border-radius: 8px;">ACTIVE</span>
                        </div>

                        <div class="row g-2 mt-3 text-center">
                            <div class="col-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Total Loan</small>
                                    <span class="fs-5 font-weight-bold text-dark" id="hist_total">Rs. 0</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Repaid Amount</small>
                                    <span class="fs-5 font-weight-bold text-success" id="hist_paid">Rs. 0</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Remaining</small>
                                    <span class="fs-5 font-weight-bold text-danger" id="hist_remaining">Rs. 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <ul class="nav nav-pills nav-justified mb-3" id="historyTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active font-weight-bold py-2" id="repayments-tab" data-toggle="pill" data-target="#repayments" data-bs-toggle="pill" data-bs-target="#repayments" type="button" role="tab">
                                    <i class="fa fa-receipt me-1"></i> Repayment Records
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link font-weight-bold py-2" id="scheduled-tab" data-toggle="pill" data-target="#scheduled" data-bs-toggle="pill" data-bs-target="#scheduled" type="button" role="tab">
                                    <i class="fa fa-calendar-alt me-1"></i> Scheduled Deductions
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content p-2" id="historyTabContent">
                            <div class="tab-pane fade show active" id="repayments" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0" id="repaymentsTable">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th>Date</th>
                                                <th>Payment Method</th>
                                                <th class="text-end">Amount</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- JS Populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="scheduled" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0" id="scheduledTable">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th>Target Month</th>
                                                <th>Status</th>
                                                <th class="text-end">Scheduled Amount</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- JS Populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-modern">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Live Search
            $('#loanSearch').on('input keyup', function() {
                var val = $(this).val().toLowerCase().trim();
                var count = 0;
                $('#loanGrid .hr-item-card').each(function() {
                    var card = $(this);
                    var text = (card.data('employee') || '') + ' ' +
                               (card.data('designation') || '') + ' ' +
                               (card.data('date') || '') + ' ' +
                               (card.data('amount') || '') + ' ' +
                               (card.data('status') || '');

                    if (!val || text.toLowerCase().indexOf(val) !== -1) {
                        card.show();
                        count++;
                    } else {
                        card.hide();
                    }
                });
                $('#loanCount').text(count + ' Records');
            });

            // Initialize Select2 in Modal when shown
            $('#addLoanModal').on('shown.bs.modal', function() {
                $('.select2').select2({
                    dropdownParent: $('#addLoanModal'),
                    width: '100%'
                });
            });

            // Live Repayment Duration Calculator
            $('#modal_loan_amount, #modal_installment_amount').on('input change', function() {
                var total = parseFloat($('#modal_loan_amount').val()) || 0;
                var inst = parseFloat($('#modal_installment_amount').val()) || 0;

                if (total > 0 && inst > 0) {
                    var months = Math.ceil(total / inst);
                    $('#calc_months_text').text(months + ' Months (' + inst.toLocaleString() + ' PKR / mo)');
                    $('#calc_preview_box').slideDown();
                } else {
                    $('#calc_preview_box').slideUp();
                }
            });
        });

        function approveLoan(id) {
            Swal.fire({
                title: 'Approve Loan Request?',
                text: "Are you sure you want to approve this loan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve Loan',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/hr/loans/' + id + '/approve',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            Swal.fire('Approved!', res.success || 'Loan approved successfully.', 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.error || 'Failed to approve loan.', 'error');
                        }
                    });
                }
            });
        }

        function rejectLoan(id) {
            Swal.fire({
                title: 'Reject Loan Request?',
                text: "This action will reject the loan application.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/hr/loans/' + id + '/reject',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            Swal.fire('Rejected!', res.success || 'Loan rejected.', 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.error || 'Failed to reject loan.', 'error');
                        }
                    });
                }
            });
        }

        function deleteLoan(id) {
            Swal.fire({
                title: 'Delete Loan Record?',
                text: "This will permanently remove this loan record and history.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/hr/loans/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            Swal.fire('Deleted!', res.success || 'Loan deleted.', 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.error || 'Failed to delete loan.', 'error');
                        }
                    });
                }
            });
        }

        function scheduleDeductionModal(id, remaining) {
            $('#schedule_loan_id').val(id);
            $('#max_sched_amount').text(remaining.toLocaleString());
            $('#schedule_amount').attr('max', remaining).val(remaining);
            $('#scheduleModal').modal('show');
        }

        function viewHistory(id) {
            $('#hist_emp_name').text('Loading Details...');
            $('#hist_emp_subtitle').text('');
            $('#repaymentsTable tbody').html('<tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin me-1"></i> Loading history...</td></tr>');
            $('#scheduledTable tbody').html('<tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin me-1"></i> Loading history...</td></tr>');
            $('#historyModal').modal('show');

            $.ajax({
                url: '/hr/loans/' + id + '/history',
                type: 'GET',
                success: function(data) {
                    if (data.employee) {
                        $('#hist_emp_name').text(data.employee.first_name + ' ' + (data.employee.last_name || ''));
                        $('#hist_emp_subtitle').text(data.employee.designation?.name || 'Staff Member');
                    } else {
                        $('#hist_emp_name').text('Loan Record #' + data.id);
                    }

                    $('#hist_total').text('Rs. ' + parseFloat(data.amount || 0).toLocaleString());
                    $('#hist_paid').text('Rs. ' + parseFloat(data.paid_amount || 0).toLocaleString());
                    $('#hist_remaining').text('Rs. ' + parseFloat(data.remaining_amount || 0).toLocaleString());
                    $('#hist_status').text((data.status || 'ACTIVE').toUpperCase());

                    // Fill Repayments
                    let repaymentHtml = '';
                    if (data.payments && data.payments.length > 0) {
                        data.payments.forEach(pay => {
                            repaymentHtml += `
                                <tr>
                                    <td><i class="fa fa-calendar-check text-success me-1"></i> ${new Date(pay.created_at).toLocaleDateString('en-GB')}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1">${pay.payment_type || 'Payroll Deduction'}</span></td>
                                    <td class="text-end font-weight-bold text-success">Rs. ${parseFloat(pay.amount).toLocaleString()}</td>
                                    <td class="small text-muted">${pay.notes || '-'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        repaymentHtml = '<tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-info-circle me-1"></i> No payments recorded yet.</td></tr>';
                    }
                    $('#repaymentsTable tbody').html(repaymentHtml);

                    // Fill Scheduled
                    let schedHtml = '';
                    if (data.scheduled_deductions && data.scheduled_deductions.length > 0) {
                        data.scheduled_deductions.forEach(sch => {
                            let statusBadge = sch.status === 'deducted' ? 'bg-success-subtle text-success border border-success' : 'bg-warning-subtle text-warning-emphasis border border-warning';
                            schedHtml += `
                                <tr>
                                    <td><i class="fa fa-calendar-alt text-primary me-1"></i> ${sch.deduction_month}</td>
                                    <td><span class="badge ${statusBadge} px-2 py-1">${sch.status.toUpperCase()}</span></td>
                                    <td class="text-end font-weight-bold text-dark">Rs. ${parseFloat(sch.amount).toLocaleString()}</td>
                                    <td class="small text-muted">${sch.notes || '-'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        schedHtml = '<tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-info-circle me-1"></i> No scheduled deductions. (Auto-deducted via regular payroll).</td></tr>';
                    }
                    $('#scheduledTable tbody').html(schedHtml);
                },
                error: function() {
                    $('#hist_emp_name').text('Error Loading Data');
                    $('#repaymentsTable tbody').html('<tr><td colspan="4" class="text-center text-danger py-3">Failed to load loan history.</td></tr>');
                }
            });
        }
    </script>
@endpush
