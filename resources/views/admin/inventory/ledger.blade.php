@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>Inventory Ledger</h2>
        <span class="page-count">{{ $ledger->total() ?? 0 }} entries</span>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
        <div>
            <span class="stat-card-label">Total Stock In (Credit)</span>
            <span class="stat-card-value">+{{ number_format($summary->total_in) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
        </div>
        <div>
            <span class="stat-card-label">Total Stock Out (Debit)</span>
            <span class="stat-card-value">{{ number_format($summary->total_out) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline></svg>
        </div>
        <div>
            <span class="stat-card-label">Current On Hand</span>
            <span class="stat-card-value">{{ number_format($summary->total_in + $summary->total_out) }}</span>
        </div>
    </div>
</div>

<form class="filters-bar" hx-get="/admin/inventory/ledger" hx-target="#ledger-table-container" hx-push-url="true">
    <input type="hidden" name="_partial" value="true">
    <div class="filter-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
        <input type="text" placeholder="Search by product name or SKU..." name="search" value="{{ $filters['search'] ?? '' }}"
            hx-trigger="keyup changed delay:300ms">
    </div>
    <select name="type" hx-trigger="change">
        <option value="">All Types</option>
        <option value="sale" {{ ($filters['type'] ?? '') === 'sale' ? 'selected' : '' }}>Sale</option>
        <option value="purchase" {{ ($filters['type'] ?? '') === 'purchase' ? 'selected' : '' }}>Purchase</option>
        <option value="opening" {{ ($filters['type'] ?? '') === 'opening' ? 'selected' : '' }}>Opening Stock</option>
        <option value="adjustment" {{ ($filters['type'] ?? '') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
        <option value="return" {{ ($filters['type'] ?? '') === 'return' ? 'selected' : '' }}>Return / Refund</option>
    </select>
    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" hx-trigger="change">
    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" hx-trigger="change">
    <button type="submit" class="btn btn-secondary">Filter</button>
</form>

<div id="ledger-table-container">
    @include('admin.inventory.partials.ledger-table')
</div>
@endsection
