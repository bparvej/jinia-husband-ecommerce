<span id="cart-count" hx-swap-oob="true" class="cart-count">0</span>

<div class="checkout-success-container">
    <!-- Animated Checkmark -->
    <div class="success-icon-wrapper">
        <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>
    </div>

    <h2>Order Placed!</h2>
    <p class="order-number">Order #{{ $order->order_number }}</p>
    <p class="success-message">Thank you for shopping with HomeI! We'll contact you shortly to confirm delivery.</p>

    <!-- Order Summary -->
    <div class="order-summary-box">
        <h4>Delivery Details</h4>
        <div class="summary-details">
            <p><strong>Recipient:</strong> {{ $order->shipping_name }}</p>
            <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
            <p><strong>Payment:</strong>
                @if ($order->payment && $order->payment->method === 'cod')
                    Cash on Delivery
                @elseif ($order->payment)
                    {{ ucfirst($order->payment->method) }}
                @else
                    Cash on Delivery
                @endif
            </p>
            <p class="summary-total"><strong>Total:</strong> ৳{{ number_format($order->total) }}</p>
        </div>
    </div>

    <!-- Contact Buttons -->
    <div class="drawer-contact-btns">
        <a href="https://wa.me/8801787656135?text=Hi!%20I%20need%20help%20with%20my%20order%20{{ $order->order_number }}" target="_blank" rel="noopener" class="drawer-whatsapp-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
        </a>
        <a href="tel:+8801787656135" class="drawer-call-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Call
        </a>
    </div>

    <button class="btn btn-primary btn-full close-cart-btn" onclick="document.getElementById('cart-drawer-close').click()">Continue Shopping</button>
</div>

<style>
.drawer-contact-btns { display: flex; gap: 10px; margin-bottom: 16px; }
.drawer-whatsapp-btn, .drawer-call-btn { flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.drawer-whatsapp-btn { background: #25d366; color: #fff; }
.drawer-whatsapp-btn:hover { background: #1da851; }
.drawer-call-btn { background: #2c2c2c; color: #fff; }
.drawer-call-btn:hover { background: #1a1a1a; }
</style>
