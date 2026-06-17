@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2>Reports & Analytics</h2>
        <p class="text-muted">Analyze your sales performance and business growth.</p>
    </div>
    <div class="header-actions">
        <select name="range" class="form-select" onchange="window.location.href='?range='+this.value">
            <option value="30days" {{ $range === '30days' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="7days" {{ $range === '7days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="12months" {{ $range === '12months' ? 'selected' : '' }}>Last 12 Months</option>
        </select>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon revenue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Revenue</span>
            <h3 class="stat-value">BDT {{ number_format($sales->totalRevenue) }}</h3>
            @if ($sales->revenueGrowth != 0)
            <span class="stat-change {{ $sales->revenueGrowth > 0 ? 'up' : 'down' }}">
                {{ $sales->revenueGrowth > 0 ? '↑' : '↓' }} {{ abs($sales->revenueGrowth) }}%
            </span>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orders">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Orders</span>
            <h3 class="stat-value">{{ $sales->totalOrders }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon products">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44476 22 3 21.5552 3 20.9932V8L21 8Z"></path><path d="M1 3H23V8H1V3Z"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Avg. Order Value</span>
            <h3 class="stat-value">BDT {{ number_format($sales->avgOrderValue) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Active Products</span>
            <h3 class="stat-value">{{ $sales->totalProducts }}</h3>
        </div>
    </div>
</div>

<div id="report-content">
    @include('admin.reports.partials.sales-report')
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.revenue { background: #ecfdf5; color: #059669; }
    .stat-icon.orders { background: #eff6ff; color: #2563eb; }
    .stat-icon.products { background: #fff7ed; color: #ea580c; }
    .stat-icon.users { background: #fdf2f8; color: #db2777; }
    .stat-label { font-size: 0.875rem; color: #6b7280; }
    .stat-value { font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; }
    .stat-change { font-size: 0.75rem; font-weight: 600; }
    .stat-change.up { color: #059669; }
    .stat-change.down { color: #dc2626; }
    .header-content h2 { font-size: 1.5rem; font-weight: 700; color: var(--admin-text); }
    .header-content p { margin: 0; }
</style>
@endsection