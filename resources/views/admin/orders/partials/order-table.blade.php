<div class="panel">
    <div class="panel-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($orders) > 0)
                    @foreach ($orders as $order)
                    <tr>
                        <td><a href="/admin/orders/{{ $order->id }}" class="order-link">{{ $order->order_number }}</a></td>
                        <td>
                            <div>
                                <span class="text-bold">{{ $order->user ? $order->user->name : 'N/A' }}</span>
                                <br><span class="text-muted text-sm">{{ $order->user ? $order->user->email : '' }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($order->payment)
                                <span class="payment-method">{{ strtoupper($order->payment->method) }}</span>
                                <br><span class="status-badge status-{{ $order->payment->status }}">{{ $order->payment->status }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-bold">৳{{ number_format($order->total) }}</td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="/admin/orders/{{ $order->id }}" class="action-btn-sm view" title="View Details">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="/admin/orders/{{ $order->id }}/invoice" class="action-btn-sm save" title="Generate Invoice" target="_blank">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="text-center text-muted">No orders found</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="pagination">
        @if ($orders->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a hx-get="?page={{ $orders->currentPage() - 1 }}&_partial=true"
               hx-target="#order-table-container"
               hx-push-url="true"
               class="page-btn">← Prev</a>
        @endif
        @for ($i = 1; $i <= $orders->lastPage(); $i++)
            <a hx-get="?page={{ $i }}&_partial=true"
               hx-target="#order-table-container"
               hx-push-url="true"
               class="page-btn {{ $i === $orders->currentPage() ? 'active' : '' }}">{{ $i }}</a>
        @endfor
        @if ($orders->hasMorePages())
            <a hx-get="?page={{ $orders->currentPage() + 1 }}&_partial=true"
               hx-target="#order-table-container"
               hx-push-url="true"
               class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
    @endif
</div>
