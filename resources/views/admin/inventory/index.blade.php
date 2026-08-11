@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>Inventory</h2>
        <span class="page-count">{{ $inventory->total() ?? 0 }} products</span>
    </div>
    <div class="page-header-right">
        <a href="/admin/inventory/ledger" class="btn btn-secondary btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line></svg>
            View Ledger
        </a>
    </div>
</div>

<div class="filters-bar">
    <div class="filter-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
        <input type="text" placeholder="Search by name or SKU..." value="{{ $filters['search'] ?? '' }}"
            hx-get="/admin/inventory" hx-target="#inventory-table-container" hx-trigger="keyup changed delay:300ms"
            name="search" hx-vals='{"_partial":"1"}'>
    </div>
</div>

<div id="inventory-table-container">
    @include('admin.inventory.partials.inventory-table')
</div>
@endsection
