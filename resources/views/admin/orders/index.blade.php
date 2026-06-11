@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>Orders</h2>
        <span class="page-count">{{ $orders->total() ?? 0 }} total</span>
    </div>
</div>

<div class="filters-bar">
    <div class="filter-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
        <input type="text" placeholder="Search by order number..." value="{{ $filters['search'] ?? '' }}"
            hx-get="/admin/orders" hx-target="#order-table-container" hx-trigger="keyup changed delay:300ms"
            name="search" hx-include="[name='status']" hx-vals='{"_partial":"1"}'>
    </div>
    <select name="status" hx-get="/admin/orders" hx-target="#order-table-container" hx-trigger="change" hx-include="[name='search']" hx-vals='{"_partial":"1"}'>
        <option value="">All Status</option>
        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="processing" {{ ($filters['status'] ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
        <option value="shipped" {{ ($filters['status'] ?? '') === 'shipped' ? 'selected' : '' }}>Shipped</option>
        <option value="delivered" {{ ($filters['status'] ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
        <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
</div>

<div id="order-table-container">
    @include('admin.orders.partials.order-table')
</div>
@endsection
