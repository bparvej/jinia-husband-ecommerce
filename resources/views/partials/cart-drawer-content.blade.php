<span id="cart-count" hx-swap-oob="true" class="cart-count">{{ $count ?? '0' }}</span>

@if (!isset($cart) || !$cart || !$cart->CartItems || count($cart->CartItems) === 0)
    <div class="cart-empty-state">
        <div class="empty-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
        </div>
        <h4>Your cart is empty</h4>
        <p>Explore our handcrafted collection and add warmth to your home.</p>
        <button class="btn btn-primary btn-sm close-cart-btn" onclick="document.getElementById('cart-drawer-close').click()">Start Shopping</button>
    </div>
@else
    @php
        $subtotal = 0;
        foreach ($cart->CartItems as $item) {
            $subtotal += floatval($item->Product->price) * $item->quantity;
        }
        $freeShipping = $subtotal >= 5000;
    @endphp
    <div class="cart-items-list">
        @foreach ($cart->CartItems as $item)
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="{{ !empty($item->Product->image) ? asset('storage/' . $item->Product->image) : '/assets/images/category-bookshelf.png' }}" alt="{{ $item->Product->name }}">
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-title">{{ $item->Product->name }}</h4>
                    <div class="cart-item-price">
                        <span class="price-current">৳{{ number_format($item->Product->price) }}</span>
                        @if ($item->Product->compare_price)
                            <span class="price-old">৳{{ number_format($item->Product->compare_price) }}</span>
                        @endif
                    </div>
                    <div class="cart-item-actions">
                        <div class="qty-selector">
                            <button class="qty-btn"
                                    hx-post="/cart/update/{{ $item->product_id }}"
                                    hx-vals='{"quantity": "{{ $item->quantity - 1 }}"}'
                                    hx-encoding="multipart/form-data"
                                    hx-target="#cart-drawer-body"
                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>&minus;</button>
                            <span class="qty-val">{{ $item->quantity }}</span>
                            <button class="qty-btn"
                                    hx-post="/cart/update/{{ $item->product_id }}"
                                    hx-vals='{"quantity": "{{ $item->quantity + 1 }}"}'
                                    hx-encoding="multipart/form-data"
                                    hx-target="#cart-drawer-body">+</button>
                        </div>
                        <button class="cart-item-remove"
                                hx-post="/cart/remove/{{ $item->product_id }}"
                                hx-target="#cart-drawer-body"
                                aria-label="Remove item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="cart-drawer-summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>৳{{ number_format($subtotal) }}</span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span id="cart-shipping-label">{{ $freeShipping ? 'Free' : 'Select location' }}</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span id="cart-total-label">৳{{ number_format($subtotal) }}</span>
        </div>
    </div>

    <div class="checkout-section">
        <h3>Shortcut Checkout</h3>
        <p class="section-desc">Place your order instantly. No registration required.</p>

        <form hx-post="/checkout" hx-target="#cart-drawer-body" hx-indicator="#checkout-indicator" id="checkout-form">
            @csrf

            <div class="form-group">
                <label for="shipping_name">Full Name *</label>
                <input type="text" id="shipping_name" name="shipping_name" class="form-control" placeholder="E.g. Rahman Ali" required>
            </div>

            <div class="form-group">
                <label for="shipping_phone">Phone Number *</label>
                <input type="tel" id="shipping_phone" name="shipping_phone" class="form-control" placeholder="E.g. 01712345678" required>
            </div>

            <div class="form-group">
                <label for="shipping_email">Email (optional)</label>
                <input type="email" id="shipping_email" name="shipping_email" class="form-control" placeholder="For order updates via email">
            </div>

            <div class="form-group">
                <label for="shipping_address">Delivery Address *</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="2" placeholder="House no, Flat, Street, Area" required></textarea>
            </div>

            <div class="form-group">
                <label for="shipping_city">Delivery Location *</label>
                <select id="shipping_city" name="shipping_city" class="form-control" required onchange="updateCartShipping()">
                    <option value="" disabled selected>Select Location</option>
                    <option value="Dhaka"{{ $freeShipping ? ' disabled' : '' }}>Inside Dhaka — ৳80{{ $freeShipping ? ' (Free above ৳5,000)' : '' }}</option>
                    <option value="Outside Dhaka"{{ $freeShipping ? ' disabled' : '' }}>Outside Dhaka — ৳120{{ $freeShipping ? ' (Free above ৳5,000)' : '' }}</option>
                </select>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <div class="payment-methods-grid">
                    <label class="payment-option active">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <div class="payment-option-content">
                            <span class="method-title">Cash on Delivery</span>
                            <span class="method-desc">Pay when you receive the product</span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full checkout-submit-btn">
                <span>Place Order (<span id="cart-btn-total">৳{{ number_format($subtotal) }}</span>)</span>
                <div id="checkout-indicator" class="htmx-indicator spinner-sm"></div>
            </button>
        </form>

        <input type="hidden" id="cart-subtotal" value="{{ $subtotal }}">
        <script>
        (function() {
            var subtotal = {{ $subtotal }};
            var freeShipping = {{ $freeShipping ? 'true' : 'false' }};

            window.updateCartShipping = function() {
                var sel = document.getElementById('shipping_city');
                var shippingLabel = document.getElementById('cart-shipping-label');
                var totalLabel = document.getElementById('cart-total-label');
                var btnTotal = document.getElementById('cart-btn-total');
                if (!sel || !sel.value) return;
                var shipping = 0;
                if (!freeShipping) {
                    shipping = sel.value === 'Dhaka' ? 80 : 120;
                }
                var total = subtotal + shipping;
                shippingLabel.textContent = shipping === 0 ? 'Free' : '৳' + shipping.toLocaleString('en-US');
                totalLabel.textContent = '৳' + total.toLocaleString('en-US');
                btnTotal.textContent = '৳' + total.toLocaleString('en-US');
            };

            if (freeShipping) {
                var sel = document.getElementById('shipping_city');
                if (sel) {
                    var opt = sel.querySelector('option[value="Dhaka"]');
                    if (opt) { opt.disabled = false; opt.selected = true; opt.text = 'Inside Dhaka — Free'; }
                    var opt2 = sel.querySelector('option[value="Outside Dhaka"]');
                    if (opt2) { opt2.disabled = false; opt2.text = 'Outside Dhaka — Free'; }
                    window.updateCartShipping();
                }
            }
        })();
        </script>
    </div>
@endif
