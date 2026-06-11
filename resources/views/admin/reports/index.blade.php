@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2>Reports & Analytics</h2>
        <p class="text-muted">Analyze your sales performance and business growth.</p>
    </div>
    <div class="header-actions">
        <select name="range" hx-get="/api/v1/reports/sales" hx-target="#report-content" hx-indicator=".loader" class="form-select">
            <option value="30days">Last 30 Days</option>
            <option value="7days">Last 7 Days</option>
            <option value="12months">Last 12 Months</option>
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
            <h3 class="stat-value">BDT {{ number_format($sales->totalRevenue ?? 0) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orders">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Orders</span>
            <h3 class="stat-value">{{ $sales->totalOrders ?? 0 }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon products">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44476 22 3 21.5552 3 20.9932V8L21 8Z"></path><path d="M1 3H23V8H1V3Z"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Active Products</span>
            <h3 class="stat-value">{{ $sales->totalProducts ?? 0 }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Customers</span>
            <h3 class="stat-value">{{ $sales->totalCustomers ?? 0 }}</h3>
        </div>
    </div>
</div>

<div id="report-content" hx-get="/api/v1/reports/sales?range=30days" hx-trigger="load">
    <div class="loader-container">
        <div class="loader"></div>
        <p>Loading analytics data...</p>
    </div>
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
    }
    .stat-icon.revenue { background: #ecfdf5; color: #059669; }
    .stat-icon.orders { background: #eff6ff; color: #2563eb; }
    .stat-icon.products { background: #fff7ed; color: #ea580c; }
    .stat-icon.users { background: #fdf2f8; color: #db2777; }
    .stat-label { font-size: 0.875rem; color: #6b7280; }
    .stat-value { font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; }
    .loader-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem;
        color: #6b7280;
    }
</style>
@endsection
