@extends('layouts.admin')

@section('content')
<div class="dashboard-grid">
    <div class="dashboard-top-header">
        <h2>Dashboard Overview</h2>
        <button class="btn btn-secondary btn-sm"
                hx-get="/admin/dashboard?_partial=stats"
                hx-target="#stats-container"
                hx-swap="innerHTML">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
            Refresh Stats
        </button>
    </div>

    <div id="stats-container" hx-get="/admin/dashboard?_partial=stats" hx-trigger="every 30s" hx-swap="innerHTML">
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-card-icon blue">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Total Revenue</span>
                    <span class="stat-card-value">৳{{ number_format($totalRevenue ?? 0) }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Total Orders</span>
                    <span class="stat-card-value">{{ $totalOrders ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon purple">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Products</span>
                    <span class="stat-card-value">{{ $totalProducts ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon orange">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Customers</span>
                    <span class="stat-card-value">{{ $totalCustomers ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="status-cards">
            <div class="mini-stat pending">
                <span class="mini-stat-count">{{ $pendingOrders ?? 0 }}</span>
                <span class="mini-stat-label">Pending</span>
            </div>
            <div class="mini-stat processing">
                <span class="mini-stat-count">{{ $stats->processing_orders ?? 0 }}</span>
                <span class="mini-stat-label">Processing</span>
            </div>
            <div class="mini-stat delivered">
                <span class="mini-stat-count">{{ $stats->delivered_orders ?? 0 }}</span>
                <span class="mini-stat-label">Delivered</span>
            </div>
        </div>
    </div>

    <div class="dashboard-panels">
        <div class="panel">
            <div class="panel-header">
                <h3>Recent Orders</h3>
                <a href="/admin/orders" class="panel-link">View All →</a>
            </div>
            <div class="panel-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($recentOrders) && count($recentOrders) > 0)
                            @foreach ($recentOrders as $order)
                            <tr>
                                <td><a href="/admin/orders/{{ $order->id }}" class="order-link">{{ $order->order_number }}</a></td>
                                <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                                <td class="text-bold">৳{{ number_format($order->total) }}</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d M') }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center text-muted">No orders yet</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h3>Low Stock Alerts</h3>
                <a href="/admin/inventory" class="panel-link">Manage →</a>
            </div>
            <div class="panel-body">
                @if (isset($lowStock) && count($lowStock) > 0)
                    @foreach ($lowStock as $item)
                    <div class="low-stock-item">
                        <img src="{{ $item->product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $item->product->name }}" class="low-stock-img">
                        <div class="low-stock-info">
                            <span class="low-stock-name">{{ $item->product->name }}</span>
                            <span class="low-stock-sku">{{ $item->product->sku ?? 'No SKU' }}</span>
                        </div>
                        <div class="low-stock-qty {{ $item->quantity <= 5 ? 'critical' : 'warning' }}">
                            {{ $item->quantity }} left
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <p>✅ All products are well stocked!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
