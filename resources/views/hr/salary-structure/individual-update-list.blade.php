@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .custom-override-container {
            max-width: 1240px;
            margin: 0 auto;
        }

        .override-header {
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

        .override-table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .override-table-header {
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container custom-override-container">

                <!-- Header Banner -->
                <div class="override-header">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 1.4rem; width: 46px; height: 46px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-user-cog"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold" style="font-size: 1.35rem;">
                                Individual Overrides: {{ $salaryStructure->name }}
                            </h2>
                            <p class="mb-0 text-white-50" style="font-size: 0.85rem;">
                                Customize specific allowances, base salary, or deductions for individual assigned employees
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('hr.salary-structure.view-assigned', $salaryStructure->id) }}" class="btn btn-light btn-sm font-weight-bold px-3 py-2" style="border-radius: 9px;">
                        <i class="fa fa-users me-1"></i> View Assigned History
                    </a>
                </div>

                <!-- Navigation Action Links in Header -->
                <div class="quick-nav-bar">
                    <a href="{{ route('hr.salary-structure.index') }}" class="quick-nav-link">
                        <i class="fa fa-th-large text-muted"></i> All Templates
                    </a>
                    <a href="{{ route('hr.salary-structure.assign-page', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-user-plus text-muted"></i> Assign Employees
                    </a>
                    <a href="{{ route('hr.salary-structure.view-assigned', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-users text-muted"></i> Assigned Employees
                    </a>
                    <a href="{{ route('hr.salary-structure.individual-update-page', $salaryStructure->id) }}" class="quick-nav-link active">
                        <i class="fa fa-user-cog text-primary"></i> Custom Overrides
                    </a>
                    <a href="{{ route('hr.salary-structure.edit-template', $salaryStructure->id) }}" class="quick-nav-link">
                        <i class="fa fa-pencil-alt text-muted"></i> Edit Template
                    </a>
                    <a href="{{ route('hr.payroll.index') }}" class="quick-nav-link ms-auto text-primary">
                        <i class="fa fa-file-invoice-dollar"></i> Payroll Dashboard <i class="fa fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <!-- Employees Table Card -->
                <div class="override-table-card">
                    <div class="override-table-header">
                        <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 0.98rem;">
                            <i class="fa fa-users text-primary me-2"></i> Active Assigned Employees
                        </h5>
                        <span class="badge bg-light text-dark border px-2 py-1 font-weight-bold">
                            {{ $assignments->total() }} Employees
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="bg-light text-muted small text-uppercase">
                                    <th class="py-3 px-4">Employee</th>
                                    <th class="py-3 px-3">Department</th>
                                    <th class="py-3 px-3">Designation</th>
                                    <th class="py-3 px-3">Base Package</th>
                                    <th class="py-3 px-3">Status</th>
                                    <th class="py-3 px-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignments as $employee)
                                    @php
                                        $initials = strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name ?? '', 0, 1));
                                    @endphp
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="emp-avatar">{{ $initials ?: 'EM' }}</div>
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $employee->full_name }}</div>
                                                    <small class="text-muted"><i class="fa fa-id-badge me-1"></i> {{ $employee->employee_code ?? 'EMP-' . $employee->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                {{ $employee->department->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                {{ $employee->designation->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 font-weight-bold text-dark">
                                            Rs. {{ number_format($salaryStructure->base_salary + $salaryStructure->total_allowances - $salaryStructure->total_deductions, 0) }}
                                        </td>
                                        <td class="py-3 px-3">
                                            @if ($employee->status === 'active')
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1 font-weight-bold">
                                                    <i class="fa fa-check-circle me-1"></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1 text-white">{{ ucfirst($employee->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            @if ($employee->status === 'active')
                                                <a href="{{ route('hr.salary-structure.edit-individual', $employee->id) }}"
                                                    class="btn btn-sm btn-primary px-3 py-1 font-weight-bold" style="border-radius: 8px;">
                                                    <i class="fa fa-pencil-alt me-1"></i> Customize Package
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled style="border-radius: 8px;">
                                                    <i class="fa fa-ban"></i> Inactive
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                                            <p class="font-weight-bold text-dark">No active employees assigned to this structure yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-top">
                        {{ $assignments->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
