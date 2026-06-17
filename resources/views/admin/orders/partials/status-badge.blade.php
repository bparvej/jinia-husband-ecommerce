<span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
<div id="toast-container" hx-swap-oob="innerHTML">
    <div class="toast toast-success">Order status updated to <strong>{{ $order->status }}</strong></div>
</div>
