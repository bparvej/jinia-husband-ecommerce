@extends('layouts.admin')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between">
    <a href="/admin/orders" class="back-link">← Back to Orders</a>
    <a href="/admin/orders/{{ $order->id }}/invoice" class="btn-admin btn-primary-admin" target="_blank" title="Generate Invoice">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        Invoice
    </a>
</div>

<div class="order-detail-grid">
    <div class="order-detail-main">
        <div class="panel">
            <div class="panel-header">
                <h3>Order {{ $order->order_number }}</h3>
                <div id="order-status-container">
                    @include('admin.orders.partials.status-badge', ['order' => $order])
                </div>
            </div>
            <div class="panel-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($order->items)
                            @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        @if ($item->product)
                                        <img src="{{ $item->product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $item->product_name }}" class="product-thumb" onerror="this.onerror=null;this.src='/assets/images/category-bookshelf.png';">
                                        @endif
                                        <span>{{ $item->product_name }}</span>
                                    </div>
                                </td>
                                <td>৳{{ number_format($item->unit_price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-bold">৳{{ number_format($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Subtotal</td>
                            <td class="text-bold">৳{{ number_format($order->subtotal) }}</td>
                        </tr>
                        @if ($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="text-right">Discount</td>
                            <td class="text-success">-৳{{ number_format($order->discount_amount) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-right">Shipping</td>
                            <td>{{ $order->shipping_cost > 0 ? '৳' . number_format($order->shipping_cost) : 'Free' }}</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                            <td><strong>৳{{ number_format($order->total) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="order-detail-side">
        <div class="panel">
            <div class="panel-header"><h3>Update Status</h3></div>
            <div class="panel-body">
                <form hx-put="/admin/orders/{{ $order->id }}/status" hx-target="#order-status-container" hx-swap="innerHTML">
                    @csrf
                    <div class="form-group">
                        <select name="status" class="form-select">
                            @foreach (['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-primary-admin btn-full">Update Status</button>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h3>Customer</h3></div>
            <div class="panel-body">
                @if ($order->user)
                <div class="detail-row"><strong>Name:</strong> {{ $order->user->name }}</div>
                <div class="detail-row"><strong>Email:</strong> {{ $order->user->email }}</div>
                <div class="detail-row"><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</div>
                @endif
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h3>Shipping</h3></div>
            <div class="panel-body">
                <div class="detail-row"><strong>Name:</strong> {{ $order->shipping_name ?? 'N/A' }}</div>
                <div class="detail-row"><strong>Phone:</strong> {{ $order->shipping_phone ?? 'N/A' }}</div>
                <div class="detail-row"><strong>Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</div>
                <div class="detail-row"><strong>City:</strong> {{ $order->shipping_city ?? 'N/A' }}</div>
            </div>
        </div>

        @if ($order->payment)
        <div class="panel">
            <div class="panel-header"><h3>Payment</h3></div>
            <div class="panel-body">
                <div class="detail-row"><strong>Method:</strong> {{ strtoupper($order->payment->method) }}</div>
                <div class="detail-row"><strong>Status:</strong> <span class="status-badge status-{{ $order->payment->status }}">{{ $order->payment->status }}</span></div>
                <div class="detail-row"><strong>Amount:</strong> ৳{{ number_format($order->payment->amount) }}</div>
                @if ($order->payment->transaction_id)
                <div class="detail-row"><strong>Transaction:</strong> {{ $order->payment->transaction_id }}</div>
                @endif
            </div>
        </div>
        @endif

        <div class="detail-meta">
            <span>Created: {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</span>
        </div>
    </div>
</div>
@endsection
