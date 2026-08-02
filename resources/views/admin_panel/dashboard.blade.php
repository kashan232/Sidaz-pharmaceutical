@extends('admin_panel.layout.app')

@section('content')
    <!-- Google Fonts import for premium typography -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        :root {
            --dash-font: 'Outfit', sans-serif;
            --dash-primary: #6366f1;
            --dash-primary-glow: rgba(99, 102, 241, 0.15);
            --dash-success: #10b981;
            --dash-success-glow: rgba(16, 185, 129, 0.15);
            --dash-warning: #f59e0b;
            --dash-warning-glow: rgba(245, 158, 11, 0.15);
            --dash-danger: #f43f5e;
            --dash-danger-glow: rgba(244, 63, 94, 0.15);
            --dash-info: #0ea5e9;
            --dash-info-glow: rgba(14, 165, 233, 0.15);
            --dash-purple: #8b5cf6;
            --dash-purple-glow: rgba(139, 92, 246, 0.15);

            --dash-bg: #f8fafc;
            --dash-card-bg: rgba(255, 255, 255, 0.9);
            --dash-border: rgba(226, 232, 240, 0.8);
            --dash-text-main: #0f172a;
            --dash-text-muted: #64748b;
        }

        /* Set base styling */
        .dashboard-body-wrapper {
            font-family: var(--dash-font);
            color: var(--dash-text-main);
            background-color: var(--dash-bg);
            padding: 1.5rem;
            min-height: 100vh;
        }

        /* Welcome Premium Card */
        .welcome-premium-card {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 24px;
            padding: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .welcome-premium-card::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-premium-card::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: 30%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-premium-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.5rem;
            animation: fadeInDown 0.5s ease;
        }

        .welcome-premium-sub {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 1.5rem;
            font-weight: 400;
        }

        .welcome-badge-date {
            display:  inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.25rem;
            border-radius: 99px;
            font-size: 0.88rem;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Glassmorphism Stat Cards */
        .glass-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .glass-card {
            background: var(--dash-card-bg);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid var(--dash-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: var(--theme-color-hover);
        }

        .glass-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--theme-gradient);
            opacity: 0.8;
        }

        /* Dynamic Theme Tokens */
        .card-primary {
            --theme-gradient: linear-gradient(90deg, #6366f1, #8b5cf6);
            --theme-color-hover: rgba(99, 102, 241, 0.4);
            --theme-icon-bg: #eef2ff;
            --theme-icon-color: #6366f1;
        }
        .card-success {
            --theme-gradient: linear-gradient(90deg, #10b981, #059669);
            --theme-color-hover: rgba(16, 185, 129, 0.4);
            --theme-icon-bg: #ecfdf5;
            --theme-icon-color: #10b981;
        }
        .card-danger {
            --theme-gradient: linear-gradient(90deg, #f43f5e, #e11d48);
            --theme-color-hover: rgba(244, 63, 94, 0.4);
            --theme-icon-bg: #fff1f2;
            --theme-icon-color: #f43f5e;
        }
        .card-warning {
            --theme-gradient: linear-gradient(90deg, #f59e0b, #d97706);
            --theme-color-hover: rgba(245, 158, 11, 0.4);
            --theme-icon-bg: #fffbeb;
            --theme-icon-color: #f59e0b;
        }
        .card-info {
            --theme-gradient: linear-gradient(90deg, #0ea5e9, #0284c7);
            --theme-color-hover: rgba(14, 165, 233, 0.4);
            --theme-icon-bg: #f0f9ff;
            --theme-icon-color: #0ea5e9;
        }
        .card-purple {
            --theme-gradient: linear-gradient(90deg, #8b5cf6, #7c3aed);
            --theme-color-hover: rgba(139, 92, 246, 0.4);
            --theme-icon-bg: #faf5ff;
            --theme-icon-color: #8b5cf6;
        }

        .glass-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .glass-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--theme-icon-bg);
            color: var(--theme-icon-color);
            font-size: 1.25rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .glass-card-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .glass-card-value {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--dash-text-main);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .glass-card-label {
            font-size: 0.88rem;
            color: var(--dash-text-muted);
            font-weight: 500;
        }

        .glass-card-footer {
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
            font-size: 0.8rem;
            color: var(--dash-text-muted);
        }

        /* Liquidity Card Gradient Override */
        .glass-card.liquidity-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }

        .glass-card.liquidity-gradient .glass-card-value,
        .glass-card.liquidity-gradient .glass-card-label,
        .glass-card.liquidity-gradient .glass-card-footer {
            color: white;
        }

        .glass-card.liquidity-gradient .glass-card-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Quick Navigation Action Grid */
        .action-premium-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .action-premium-card {
            background: var(--dash-card-bg);
            border: 1px solid var(--dash-border);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
        }

        .action-premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px var(--hover-shadow);
            border-color: var(--hover-color);
        }

        .action-premium-card.act-sales {
            --hover-color: #10b981;
            --hover-shadow: rgba(16, 185, 129, 0.12);
            --icon-bg: #ecfdf5;
            --icon-color: #10b981;
        }

        .action-premium-card.act-purchase {
            --hover-color: #0ea5e9;
            --hover-shadow: rgba(14, 165, 233, 0.12);
            --icon-bg: #f0f9ff;
            --icon-color: #0ea5e9;
        }

        .action-premium-card.act-products {
            --hover-color: #f59e0b;
            --hover-shadow: rgba(245, 158, 11, 0.12);
            --icon-bg: #fffbeb;
            --icon-color: #f59e0b;
        }

        .action-premium-card.act-hr {
            --hover-color: #8b5cf6;
            --hover-shadow: rgba(139, 92, 246, 0.12);
            --icon-bg: #faf5ff;
            --icon-color: #8b5cf6;
        }

        .action-premium-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--icon-bg);
            color: var(--icon-color);
            font-size: 1.35rem;
            margin: 0 auto 1rem;
            transition: all 0.3s;
        }

        .action-premium-card:hover .action-premium-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .action-premium-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--dash-text-main);
            margin-bottom: 0.25rem;
        }

        .action-premium-desc {
            font-size: 0.8rem;
            color: var(--dash-text-muted);
            font-weight: 500;
        }

        /* Dynamic Panel Layout */
        .dashboard-panels-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dashboard-panels-row.full-width {
            grid-template-columns: 1fr;
        }

        .panel-card {
            background: var(--dash-card-bg);
            border-radius: 24px;
            border: 1px solid var(--dash-border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.015);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dash-text-main);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-title i {
            color: var(--dash-primary);
        }

        .panel-body {
            padding: 2rem;
            position: relative;
            flex: 1;
        }

        /* Filter Switcher Tabs */
        .filter-tab-group {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 99px;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .filter-tab-btn {
            background: transparent;
            border: none;
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--dash-text-muted);
            cursor: pointer;
            transition: all 0.25s;
        }

        .filter-tab-btn:hover {
            color: var(--dash-text-main);
        }

        .filter-tab-btn.active {
            background: white;
            color: var(--dash-primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Premium Top List Item Layout */
        .premium-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            transition: all 0.25s;
        }

        .premium-list-item:hover {
            transform: translateX(4px);
        }

        .premium-list-item:last-child {
            border-bottom: none;
        }

        .premium-list-details {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .premium-list-rank {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--dash-text-muted);
            transition: all 0.3s;
        }

        .premium-list-item:nth-child(1) .premium-list-rank {
            background: #fef3c7;
            color: #d97706;
        }
        .premium-list-item:nth-child(2) .premium-list-rank {
            background: #e2e8f0;
            color: #475569;
        }
        .premium-list-item:nth-child(3) .premium-list-rank {
            background: #ffedd5;
            color: #ea580c;
        }

        .premium-list-title {
            font-weight: 700;
            color: var(--dash-text-main);
            font-size: 0.95rem;
        }

        .premium-list-subtitle {
            font-size: 0.78rem;
            color: var(--dash-text-muted);
            margin-top: 1px;
        }

        .premium-list-value {
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--dash-primary);
            text-align: right;
        }

        /* Section Subheading Styling */
        .dash-section-subhead {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--dash-text-muted);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dash-section-subhead::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0, 0, 0, 0.05);
        }

        /* Responsive Layout Rules */
        @media (max-width: 1024px) {
            .dashboard-panels-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .welcome-premium-card {
                padding: 1.75rem;
            }
            .welcome-premium-title {
                font-size: 1.75rem;
            }
            .glass-stat-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <div class="main-content">
        <div class="main-content-inner dashboard-body-wrapper">
            <div class="container-fluid" style="padding: 0;">

                <!-- Welcome Premium Header Card -->
                <div class="welcome-premium-card">
                    <div class="welcome-premium-content">
                    <h1 class="welcome-premium-title"> Welcome Back {{ auth()->user()->name ?? 'Admin' }}! 👋</h1>
                        <p class="welcome-premium-sub">Here is your absolute live business diagnostic dashboard statistics.</p>
                        <div class="welcome-badge-date">
                            <i class="fa fa-calendar-alt"></i>
                            {{ now()->format('l, jS F Y') }}
                        </div>
                    </div>
                </div>

                <!-- Sync Alert Banner (Only shown if local environment) -->
                @if(config('app.env') === 'local')
                <div class="alert alert-info border-0 rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center shadow-sm" style="background: rgba(99, 102, 241, 0.08); border-left: 5px solid var(--dash-primary) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="glass-card-icon" style="background: var(--dash-primary-glow); color: var(--dash-primary); width:40px; height:40px; border-radius:10px;">
                            <i class="fa fa-sync-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark" style="font-family: var(--dash-font);">Local Server Offline Sync</h6>
                            <p class="mb-0 text-muted small" style="font-family: var(--dash-font);">Sync your local sales and customers to the cloud database when online.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold" id="btnSyncCloud" style="border-radius: 99px; font-size: 0.85rem; font-family: var(--dash-font); box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);">
                        <i class="fa fa-cloud-upload-alt me-1"></i> Sync to Cloud
                    </button>
                </div>
                @endif

                <!-- Quick Navigation Premium Grid -->
                <div class="action-premium-grid">
                    @can('sales.create')
                        <a href="{{ route('sale.index') }}" class="action-premium-card act-sales">
                            <div class="action-premium-icon"><i class="fa fa-shopping-cart"></i></div>
                            <div class="action-premium-title">Create Sale</div>
                            <div class="action-premium-desc">Issue invoices & POS</div>
                        </a>
                    @endcan

                    @can('purchases.create')
                        <a href="{{ route('Purchase.home') }}" class="action-premium-card act-purchase">
                            <div class="action-premium-icon"><i class="fa fa-truck"></i></div>
                            <div class="action-premium-title">Record Purchase</div>
                            <div class="action-premium-desc">Add product stock</div>
                        </a>
                    @endcan

                    @can('products.view')
                        <a href="{{ route('product') }}" class="action-premium-card act-products">
                            <div class="action-premium-icon"><i class="fa fa-box"></i></div>
                            <div class="action-premium-title">Inventory</div>
                            <div class="action-premium-desc">Manage products</div>
                        </a>
                    @endcan

                    @can('hr.employees.view')
                        <a href="{{ route('hr.employees.index') }}" class="action-premium-card act-hr">
                            <div class="action-premium-icon"><i class="fa fa-users"></i></div>
                            <div class="action-premium-title">HR Directory</div>
                            <div class="action-premium-desc">Manage employees</div>
                        </a>
                    @endcan
                </div>

                <!-- Financial Health Metrics (Accounting-based) -->
                @if (isset($financialSummary) && !empty($financialSummary))
                    <div class="dash-section-subhead">
                        <i class="fa fa-wallet"></i> Financial Health (Today)
                    </div>
                    <div class="glass-stat-grid">
                        <!-- Sales This Month -->
                        <div class="glass-card card-success">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-shopping-cart"></i></div>
                                <span class="glass-card-badge text-success bg-light" style="background:#eefdf5 !important;">Sales</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($salesThisMonth, 0) }}</div>
                                <div class="glass-card-label">Today's Sales</div>
                            </div>
                        </div>

                        <!-- Purchase -->
                        <div class="glass-card card-danger">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-money-bill-wave"></i></div>
                                <span class="glass-card-badge text-danger bg-light" style="background:#fff1f2 !important;">Operations</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($purchasesThisMonth, 0) }}</div>
                                <div class="glass-card-label">Today's Purchase</div>
                            </div>
                        </div>

                        <!-- Payables -->
                        <div class="glass-card card-warning">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-file-invoice"></i></div>
                                <span class="glass-card-badge text-warning bg-light" style="background:#fffbeb !important;">Liabilities</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($financialSummary['payables'] ?? 0, 0) }}</div>
                                <div class="glass-card-label">Total Payables</div>
                            </div>
                        </div>

                        <!-- Receivables -->
                        <div class="glass-card card-primary">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-file-invoice-dollar"></i></div>
                                <span class="glass-card-badge text-primary bg-light" style="background:#eef2ff !important;">Liabilities</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalReceivables, 0) }}</div>
                                <div class="glass-card-label">Total Receivables (Customer Credit)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Profitability & Performance Section -->
                    <div class="dash-section-subhead" style="margin-top: 2rem;">
                        <i class="fa fa-chart-pie text-success"></i> Profitability & Performance (Today)
                    </div>
                    <div class="dashboard-panels-row" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 1.5rem; width: 100%;">
                        <!-- Net Profit Card -->
                        <div class="panel-card" style="flex: 1; min-width: 320px; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.3); padding: 1.5rem; display: flex; flex-direction: column;">
                            <div class="panel-header" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                                <div class="panel-title" style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">
                                    <i class="fa fa-wallet text-success" style="margin-right: 0.5rem;"></i> Net Profit Breakdown
                                </div>
                            </div>
                            <div class="panel-body d-flex flex-column align-items-center justify-content-center" style="flex-grow: 1;">
                                <div class="text-center mb-4">
                                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Net Profit</span>
                                    <h2 class="fw-bold text-success mt-1" style="font-size: 2.5rem; margin-bottom: 0.25rem;">Rs {{ number_format($profitThisMonth, 0) }}</h2>
                                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0;">Net Revenue minus Cost of Goods Sold</p>
                                </div>
                                <div style="width: 100%;">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #475569;">
                                        <span>Net Revenue</span>
                                        <span class="fw-bold">Rs {{ number_format($totalRevenueThisMonth, 0) }}</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 6px; border-radius: 3px; background-color: #e2e8f0; overflow: hidden;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #475569;">
                                        <span>Cost of Goods Sold (COGS)</span>
                                        <span class="fw-bold">Rs {{ number_format($totalCostThisMonth, 0) }}</span>
                                    </div>
                                    @php
                                        $costPercentage = $totalRevenueThisMonth > 0 ? min(100, round(($totalCostThisMonth / $totalRevenueThisMonth) * 100)) : 0;
                                    @endphp
                                    <div class="progress" style="height: 6px; border-radius: 3px; background-color: #e2e8f0; overflow: hidden;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $costPercentage }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profitability Chart Card -->
                        <div class="panel-card" style="flex: 1.5; min-width: 350px; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.3); padding: 1.5rem; display: flex; flex-direction: column;">
                            <div class="panel-header" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                                <div class="panel-title" style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">
                                    <i class="fa fa-chart-bar text-primary" style="margin-right: 0.5rem;"></i> Revenue vs Cost vs Net Profit
                                </div>
                            </div>
                            <div class="panel-body d-flex align-items-center justify-content-center" style="flex-grow: 1;">
                                <div style="position: relative; height: 200px; width: 100%;">
                                    <canvas id="chartJsProfitability"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-section-subhead">
                        <i class="fa fa-exchange-alt"></i> Cash Flow & Payments (Today)
                    </div>
                    <div class="glass-stat-grid">
                        <!-- Payments Received (In) -->
                        <a href="{{ route('all_recepit_vochers') }}" class="glass-card card-info text-decoration-none" style="cursor: pointer;">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-arrow-down"></i></div>
                                <span class="glass-card-badge text-info bg-light" style="background:#f0f9ff !important;">Receipts</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($paymentInMonth, 0) }}</div>
                                <div class="glass-card-label">Payment In (Today)</div>
                            </div>
                            <div class="glass-card-footer">
                                Overall Received: <strong>Rs {{ number_format($paymentInOverall, 0) }}</strong>
                            </div>
                        </a>

                        <!-- Payments Settled (Out) -->
                        <a href="{{ route('all_Payment_vochers') }}" class="glass-card card-danger text-decoration-none" style="cursor: pointer;">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-arrow-up"></i></div>
                                <span class="glass-card-badge text-danger bg-light" style="background:#fff1f2 !important;">Payments</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($paymentOutMonth, 0) }}</div>
                                <div class="glass-card-label">Payment Out (Today)</div>
                            </div>
                            <div class="glass-card-footer">
                                Overall Settled: <strong>Rs {{ number_format($paymentOutOverall, 0) }}</strong>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Cash & Bank Ledger Balances -->
                @if (isset($cashAndBankAccounts) && $cashAndBankAccounts->isNotEmpty())
                    <div class="dash-section-subhead">
                        <i class="fa fa-university"></i> Bank & Ledger Liquidity
                    </div>
                    <div class="glass-stat-grid">
                        @foreach ($cashAndBankAccounts as $account)
                            @php
                                $isCash = strtolower($account->head->name) == 'cash';
                                $themeClass = $isCash ? 'card-primary' : 'card-purple';
                                $iconName = $isCash ? 'fa-wallet' : 'fa-university';
                            @endphp
                            <div class="glass-card {{ $themeClass }}">
                                <div class="glass-card-header">
                                    <div class="glass-card-icon"><i class="fas {{ $iconName }}"></i></div>
                                    <span class="glass-card-badge text-dark bg-light px-2.5 rounded-pill">{{ $account->head->name }}</span>
                                </div>
                                <div>
                                    <div class="glass-card-value">Rs {{ number_format($account->current_balance, 2) }}</div>
                                    <div class="glass-card-label fw-bold text-dark">{{ $account->title }}</div>
                                </div>
                                <div class="glass-card-footer d-flex justify-content-between align-items-center">
                                    <span>Account Balance</span>
                                    <a href="{{ route('accounts.ledger', $account->id) }}" class="text-decoration-none text-primary fw-bold">
                                        Ledger <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <!-- Total Liquidity Combined Card -->
                        <div class="glass-card liquidity-gradient">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fas fa-coins"></i></div>
                                <span class="glass-card-badge bg-white text-success rounded-pill fw-bold">Total Cash</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalCashAndBankBalance, 2) }}</div>
                                <div class="glass-card-label">Combined Cash & Bank Assets</div>
                            </div>
                            <div class="glass-card-footer">
                                Active Liquid Funds
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Operations & Performance Summary (Legacy Metrics) -->
                <div class="dash-section-subhead">
                    <i class="fa fa-chart-bar"></i> Operations Performance
                </div>
                <div class="glass-stat-grid">
                    @can('sales.view')
                        <div class="glass-card card-success">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-shopping-cart"></i></div>
                                <span class="glass-card-badge text-success bg-light" style="background:#ecfdf5 !important;">Performance</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalSales, 0) }}</div>
                                <div class="glass-card-label">Total Lifetime Sales</div>
                            </div>
                        </div>
                    @endcan

                    @can('purchases.view')
                        <div class="glass-card card-primary">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-file-invoice-dollar"></i></div>
                                <span class="glass-card-badge text-primary bg-light" style="background:#eef2ff !important;">Performance</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalPurchases, 0) }}</div>
                                <div class="glass-card-label">Total Lifetime Purchases</div>
                            </div>
                        </div>
                    @endcan

                    @can('sales.returns.view')
                        <div class="glass-card card-danger">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-undo-alt"></i></div>
                                <span class="glass-card-badge text-danger bg-light" style="background:#fff1f2 !important;">Operations</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalSalesReturns, 0) }}</div>
                                <div class="glass-card-label">Total Sales Returns</div>
                            </div>
                        </div>
                    @endcan

                    @can('purchase.returns.view')
                        <div class="glass-card card-warning">
                            <div class="glass-card-header">
                                <div class="glass-card-icon"><i class="fa fa-undo"></i></div>
                                <span class="glass-card-badge text-warning bg-light" style="background:#fffbeb !important;">Operations</span>
                            </div>
                            <div>
                                <div class="glass-card-value">Rs {{ number_format($totalPurchaseReturns, 0) }}</div>
                                <div class="glass-card-label">Total Purchase Returns</div>
                            </div>
                        </div>
                    @endcan
                </div>

                <!-- Database Volume Counts Summary -->
                <div class="glass-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                    @can('categories.view')
                        <div class="glass-card card-primary" style="padding: 1.25rem;">
                            <div class="glass-card-header mb-2">
                                <span class="text-uppercase fw-bold text-muted" style="font-size:0.75rem;">Categories</span>
                                <div class="glass-card-icon" style="width:36px; height:36px; font-size:1rem;"><i class="fa fa-layer-group"></i></div>
                            </div>
                            <div class="glass-card-value" style="font-size:1.5rem;">{{ $categoryCount }}</div>
                        </div>
                    @endcan

                    @can('subcategories.view')
                        <div class="glass-card card-success" style="padding: 1.25rem;">
                            <div class="glass-card-header mb-2">
                                <span class="text-uppercase fw-bold text-muted" style="font-size:0.75rem;">Subcategories</span>
                                <div class="glass-card-icon" style="width:36px; height:36px; font-size:1rem;"><i class="fa fa-sitemap"></i></div>
                            </div>
                            <div class="glass-card-value" style="font-size:1.5rem;">{{ $subcategoryCount }}</div>
                        </div>
                    @endcan

                    @can('products.view')
                        <div class="glass-card card-warning" style="padding: 1.25rem;">
                            <div class="glass-card-header mb-2">
                                <span class="text-uppercase fw-bold text-muted" style="font-size:0.75rem;">Products</span>
                                <div class="glass-card-icon" style="width:36px; height:36px; font-size:1rem;"><i class="fa fa-box-open"></i></div>
                            </div>
                            <div class="glass-card-value" style="font-size:1.5rem;">{{ $productCount }}</div>
                        </div>
                    @endcan

                    @can('customers.view')
                        <div class="glass-card card-info" style="padding: 1.25rem;">
                            <div class="glass-card-header mb-2">
                                <span class="text-uppercase fw-bold text-muted" style="font-size:0.75rem;">Customers</span>
                                <div class="glass-card-icon" style="width:36px; height:36px; font-size:1rem;"><i class="fa fa-users"></i></div>
                            </div>
                            <div class="glass-card-value" style="font-size:1.5rem;">{{ $customerscount }}</div>
                        </div>
                    @endcan
                </div>

                <!-- Premium Chart.js Sections -->
                <div class="dashboard-panels-row" style="margin-top: 2rem;">
                    @can('sales.view')
                        <div class="panel-card">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <i class="fa fa-chart-line"></i> Sales Analytics
                                </div>
                                <div class="filter-tab-group" id="salesFilterGroup">
                                    <button class="filter-tab-btn active" data-filter="daily">Daily</button>
                                    <button class="filter-tab-btn" data-filter="weekly">Weekly</button>
                                    <button class="filter-tab-btn" data-filter="monthly">Monthly</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <canvas id="chartJsSales" style="max-height: 330px;"></canvas>
                            </div>
                        </div>
                    @endcan

                    @can('purchases.view')
                        <div class="panel-card">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <i class="fa fa-chart-area"></i> Purchase Analytics
                                </div>
                                <div class="filter-tab-group" id="purchaseFilterGroup">
                                    <button class="filter-tab-btn active" data-filter="daily">Daily</button>
                                    <button class="filter-tab-btn" data-filter="weekly">Weekly</button>
                                    <button class="filter-tab-btn" data-filter="monthly">Monthly</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <canvas id="chartJsPurchase" style="max-height: 330px;"></canvas>
                            </div>
                        </div>
                    @endcan
                </div>

                <!-- Cash Flow Breakdown Panel Card -->
                <div class="dashboard-panels-row full-width" style="margin-top: 2rem;">
                    <div class="panel-card">
                        <div class="panel-header">
                            <div class="panel-title">
                                <i class="fa fa-exchange-alt"></i> Cash Flow & Vouchers Summary (This Month)
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-4 mb-md-0 d-flex justify-content-center">
                                    <div style="position: relative; width: 200px; height: 200px;">
                                        <canvas id="chartJsCashFlow"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <div class="small text-muted mb-1" style="font-weight:600;">Receipts (Money In)</div>
                                            <h5 class="fw-bold text-success" style="font-size: 1.35rem; font-weight:800;">Rs {{ number_format($paymentInMonth, 2) }}</h5>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="small text-muted mb-1" style="font-weight:600;">Payments (Money Out)</div>
                                            <h5 class="fw-bold text-danger" style="font-size: 1.35rem; font-weight:800;">Rs {{ number_format($paymentOutMonth, 2) }}</h5>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted mb-1" style="font-weight:600;">Total Liquidity</div>
                                            <h5 class="fw-bold text-primary" style="font-size: 1.35rem; font-weight:800;">Rs {{ number_format($totalCashAndBankBalance, 2) }}</h5>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted mb-1" style="font-weight:600;">Monthly Cash Spread</div>
                                            @php $spread = $paymentInMonth - $paymentOutMonth; @endphp
                                            <h5 class="fw-bold {{ $spread >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 1.35rem; font-weight:800;">
                                                Rs {{ number_format($spread, 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 10 Products and Customers Premium Analytics Panels -->
                @can('sales.view')
                    <div class="dashboard-panels-row">
                        <!-- Top Products -->
                        <div class="panel-card">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <i class="fa fa-fire text-danger" style="color: var(--dash-danger) !important;"></i> Top Products (Qty Sold)
                                </div>
                            </div>
                            <div class="panel-body">
                                @if(isset($topProducts) && count($topProducts) > 0)
                                    <div class="row align-items-center">
                                        <div class="col-md-5 mb-4 mb-md-0 d-flex justify-content-center">
                                            <div style="position: relative; width: 220px; height: 220px;">
                                                <canvas id="chartJsTopProducts"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div style="max-height: 280px; overflow-y: auto; padding-right: 8px;">
                                                @php $rank = 1; @endphp
                                                @foreach($topProducts as $tp)
                                                    <div class="premium-list-item">
                                                        <div class="premium-list-details">
                                                            <div class="premium-list-rank">{{ $rank++ }}</div>
                                                            <div>
                                                                <div class="premium-list-title">{{ $tp->product_name ?: 'Standard Item' }}</div>
                                                                <div class="premium-list-subtitle">Rev: Rs {{ number_format($tp->total_revenue ?? 0) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="premium-list-value">
                                                            {{ number_format($tp->total_qty) }} units
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">No top selling products logged.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Top Customers -->
                        <div class="panel-card">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <i class="fa fa-crown text-warning" style="color: var(--dash-warning) !important;"></i> Top Customers (Sales Vol)
                                </div>
                            </div>
                            <div class="panel-body">
                                @if(isset($topCustomers) && count($topCustomers) > 0)
                                    <div class="row align-items-center">
                                        <div class="col-md-5 mb-4 mb-md-0 d-flex justify-content-center">
                                            <div style="position: relative; width: 220px; height: 220px;">
                                                <canvas id="chartJsTopCustomers"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div style="max-height: 280px; overflow-y: auto; padding-right: 8px;">
                                                @php $rank = 1; @endphp
                                                @foreach($topCustomers as $tc)
                                                    <div class="premium-list-item">
                                                        <div class="premium-list-details">
                                                            <div class="premium-list-rank">{{ $rank++ }}</div>
                                                            <div>
                                                                <div class="premium-list-title">{{ $tc->customer_name ?: 'Walk-in Customer' }}</div>
                                                                <div class="premium-list-subtitle">{{ $tc->total_orders }} orders completed</div>
                                                            </div>
                                                        </div>
                                                        <div class="premium-list-value" style="color: var(--dash-success);">
                                                            Rs {{ number_format($tc->total_sales) }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">No customer invoice records found.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endcan

                <!-- Low Stock Alarm Section -->
                @can('products.view')
                    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        <div class="dashboard-panels-row full-width">
                            <div class="panel-card">
                                <div class="panel-header">
                                    <div class="panel-title">
                                        <i class="fa fa-exclamation-triangle" style="color: #f43f5e;"></i>
                                        Low Stock Alarm (Cartons)
                                        <span class="badge ms-2" style="background:#fff1f2; color:#f43f5e; font-size:0.75rem; font-weight:700; padding:4px 12px; border-radius:99px;">
                                            {{ $lowStockProducts->count() }} Alert Products
                                        </span>
                                    </div>
                                    <a href="{{ route('product') }}?status=active" class="btn btn-sm btn-outline-danger" style="font-size:0.8rem; border-radius:99px; font-weight:600; padding:6px 14px;">
                                        Manage Inventory <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                <div class="panel-body">
                                    <div style="position: relative; height: 320px; width: 100%;">
                                        <canvas id="chartJsLowStock"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan

            </div>
        </div>
    </div>

    <!-- Chart.js and Custom Redesign Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Raw PHP Data arrays
            const salesStats = @json($salesChartStats);
            const purchaseStats = @json($purchaseChartStats);
            const topProductsData = @json($topProducts ?? []);
            const topCustomersData = @json($topCustomers ?? []);

            // Helper to build gradients for Chart.js
            function createChartGradient(ctx, colorStart, colorEnd) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            }

            // ==========================================
            // 1. SALES CHART.JS LINE GRAPH
            // ==========================================
            const salesCanvas = document.getElementById('chartJsSales');
            if (salesCanvas) {
                const salesCtx = salesCanvas.getContext('2d');

                // Set custom options for dynamic updates
                const salesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: salesStats.daily.categories,
                        datasets: [{
                            label: 'Sales Revenue',
                            data: salesStats.daily.series[0].data,
                            borderColor: '#10b981',
                            borderWidth: 3,
                            backgroundColor: createChartGradient(salesCtx, 'rgba(16, 185, 129, 0.25)', 'rgba(16, 185, 129, 0.0)'),
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Outfit', size: 13, weight: 'bold' },
                                bodyFont: { family: 'Outfit', size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rs ' + parseFloat(context.raw).toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Outfit', size: 11 }, color: '#64748b' }
                            },
                            y: {
                                grid: { color: 'rgba(0,0,0,0.03)', drawTicks: false },
                                border: { dash: [4, 4] },
                                ticks: {
                                    font: { family: 'Outfit', size: 11 },
                                    color: '#64748b',
                                    callback: val => 'Rs ' + val.toLocaleString()
                                }
                            }
                        }
                    }
                });

                // Handler for switching intervals
                document.querySelectorAll('#salesFilterGroup .filter-tab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('#salesFilterGroup .filter-tab-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const mode = this.dataset.filter;

                        salesChart.data.labels = salesStats[mode].categories;
                        salesChart.data.datasets[0].data = salesStats[mode].series[0].data;
                        salesChart.update();
                    });
                });
            }

            // ==========================================
            // 2. PURCHASE CHART.JS LINE GRAPH
            // ==========================================
            const purchaseCanvas = document.getElementById('chartJsPurchase');
            if (purchaseCanvas) {
                const purchaseCtx = purchaseCanvas.getContext('2d');

                const purchaseChart = new Chart(purchaseCtx, {
                    type: 'line',
                    data: {
                        labels: purchaseStats.daily.categories,
                        datasets: [{
                            label: 'Purchases',
                            data: purchaseStats.daily.series[0].data,
                            borderColor: '#6366f1',
                            borderWidth: 3,
                            backgroundColor: createChartGradient(purchaseCtx, 'rgba(99, 102, 241, 0.25)', 'rgba(99, 102, 241, 0.0)'),
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#6366f1',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Outfit', size: 13, weight: 'bold' },
                                bodyFont: { family: 'Outfit', size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rs ' + parseFloat(context.raw).toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Outfit', size: 11 }, color: '#64748b' }
                            },
                            y: {
                                grid: { color: 'rgba(0,0,0,0.03)', drawTicks: false },
                                border: { dash: [4, 4] },
                                ticks: {
                                    font: { family: 'Outfit', size: 11 },
                                    color: '#64748b',
                                    callback: val => 'Rs ' + val.toLocaleString()
                                }
                            }
                        }
                    }
                });

                // Handler for switching intervals
                document.querySelectorAll('#purchaseFilterGroup .filter-tab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('#purchaseFilterGroup .filter-tab-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        const mode = this.dataset.filter;

                        purchaseChart.data.labels = purchaseStats[mode].categories;
                        purchaseChart.data.datasets[0].data = purchaseStats[mode].series[0].data;
                        purchaseChart.update();
                    });
                });
            }

            // ==========================================
            // 3. TOP PRODUCTS DOUGHNUT
            // ==========================================
            const prodCanvas = document.getElementById('chartJsTopProducts');
            if (prodCanvas && topProductsData.length > 0) {
                const prodNames = topProductsData.map(p => p.product_name || 'Standard Item');
                const prodQtys = topProductsData.map(p => parseFloat(p.total_qty) || 0);

                new Chart(prodCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: prodNames,
                        datasets: [{
                            data: prodQtys,
                            backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308', '#84cc16', '#22c55e', '#06b6d4', '#3b82f6'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 10,
                                bodyFont: { family: 'Outfit', size: 11 },
                                callbacks: {
                                    label: context => ` ${context.label}: ${context.raw} units`
                                }
                            }
                        }
                    }
                });
            }

            // ==========================================
            // 4. TOP CUSTOMERS DOUGHNUT
            // ==========================================
            const custCanvas = document.getElementById('chartJsTopCustomers');
            if (custCanvas && topCustomersData.length > 0) {
                const custNames = topCustomersData.map(c => c.customer_name || 'Walk-in');
                const custSales = topCustomersData.map(c => parseFloat(c.total_sales) || 0);

                new Chart(custCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: custNames,
                        datasets: [{
                            data: custSales,
                            backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#ef4444', '#14b8a6', '#6366f1', '#eab308', '#22c55e'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 10,
                                bodyFont: { family: 'Outfit', size: 11 },
                                callbacks: {
                                    label: context => ` ${context.label}: Rs ${parseFloat(context.raw).toLocaleString()}`
                                }
                            }
                        }
                    }
                });
            }

            // ==========================================
            // 5. LOW STOCK DOUBLE BAR CHART (Chart.js)
            // ==========================================
            const lsCanvas = document.getElementById('chartJsLowStock');
            if (lsCanvas) {
                const lowStockData = @json($lowStockProducts ?? collect());
                if (lowStockData.length > 0) {
                    const lsNames = lowStockData.map(p => p.item_name ? (p.item_name.length > 20 ? p.item_name.substring(0, 20) + '…' : p.item_name) : 'Unknown');
                    const lsStock = lowStockData.map(p => parseFloat(p.current_cartons) || 0);
                    const lsAlert = lowStockData.map(p => parseFloat(p.alert_carton_quantity) || 0);

                    const lsCtx = lsCanvas.getContext('2d');
                    new Chart(lsCtx, {
                        type: 'bar',
                        data: {
                            labels: lsNames,
                            datasets: [
                                {
                                    label: 'Alert Level',
                                    data: lsAlert,
                                    backgroundColor: 'rgba(244, 63, 94, 0.85)',
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.7
                                },
                                {
                                    label: 'Current Stock',
                                    data: lsStock,
                                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.7
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: { font: { family: 'Outfit', size: 12, weight: 'bold' }, color: '#475569' }
                                },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    padding: 12,
                                    bodyFont: { family: 'Outfit', size: 12 },
                                    callbacks: {
                                        label: context => ` ${context.dataset.label}: ${context.raw} cartons`
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { family: 'Outfit', size: 11 }, color: '#475569' }
                                },
                                y: {
                                    grid: { color: 'rgba(0,0,0,0.03)' },
                                    ticks: {
                                        font: { family: 'Outfit', size: 11 },
                                        color: '#475569',
                                        callback: val => val + ' ctns'
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // ==========================================
            // 6. SYNC TO CLOUD HANDLER
            // ==========================================
            const btnSync = document.getElementById('btnSyncCloud');
            if (btnSync) {
                btnSync.addEventListener('click', function() {
                    btnSync.disabled = true;
                    const originalHtml = btnSync.innerHTML;
                    btnSync.innerHTML = '<i class="fa fa-sync-alt fa-spin me-1"></i> Syncing...';

                    Swal.fire({
                        title: 'Synchronizing...',
                        text: 'Please wait while we sync your local data with the cloud.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('admin.sync_to_cloud') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        btnSync.disabled = false;
                        btnSync.innerHTML = originalHtml;

                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sync Successful',
                                text: data.message,
                                confirmButtonColor: '#6366f1'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sync Failed',
                                text: data.message || 'An error occurred during synchronization.',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    })
                    .catch(error => {
                        btnSync.disabled = false;
                        btnSync.innerHTML = originalHtml;
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'Could not connect to the local server or cloud database. Please verify internet connection.',
                            confirmButtonColor: '#6366f1'
                        });
                    });
                });
            }

            // ==========================================
            // 7. CASH FLOW BREAKDOWN DOUGHNUT
            // ==========================================
            const cfCanvas = document.getElementById('chartJsCashFlow');
            if (cfCanvas) {
                const paymentIn = parseFloat('{{ $paymentInMonth }}') || 0;
                const paymentOut = parseFloat('{{ $paymentOutMonth }}') || 0;

                new Chart(cfCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Receipts (In)', 'Payments (Out)'],
                        datasets: [{
                            data: [paymentIn, paymentOut],
                            backgroundColor: ['#10b981', '#f43f5e'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 10,
                                bodyFont: { family: 'Outfit', size: 11 },
                                callbacks: {
                                    label: context => ` ${context.label}: Rs ${parseFloat(context.raw).toLocaleString()}`
                                }
                            }
                        }
                    }
                });
            }

            // ==========================================
            // 8. PROFITABILITY BAR CHART
            // ==========================================
            const profCanvas = document.getElementById('chartJsProfitability');
            if (profCanvas) {
                const totalRevenue = parseFloat('{{ $totalRevenueThisMonth }}') || 0;
                const totalCost = parseFloat('{{ $totalCostThisMonth }}') || 0;
                const netProfit = parseFloat('{{ $profitThisMonth }}') || 0;

                new Chart(profCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Revenue', 'Cost', 'Net Profit'],
                        datasets: [{
                            label: 'Amount',
                            data: [totalRevenue, totalCost, netProfit],
                            backgroundColor: ['#10b981', '#ef4444', '#3b82f6'],
                            borderRadius: 6,
                            borderWidth: 0,
                            barThickness: 35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 10,
                                bodyFont: { family: 'Outfit', size: 12 },
                                callbacks: {
                                    label: context => ` Rs ${parseFloat(context.raw).toLocaleString()}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { family: 'Outfit', size: 10 },
                                    callback: value => 'Rs ' + parseFloat(value).toLocaleString()
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

        });
    </script>
@endsection
