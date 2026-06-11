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
        $shippingCost = $subtotal >= 5000 ? 0 : 200;
        $total = $subtotal + $shippingCost;
    @endphp
    <div class="cart-items-list">
        @foreach ($cart->CartItems as $item)
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="{{ $item->Product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $item->Product->name }}">
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
                                    hx-target="#cart-drawer-body"
                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>&minus;</button>
                            <span class="qty-val">{{ $item->quantity }}</span>
                            <button class="qty-btn"
                                    hx-post="/cart/update/{{ $item->product_id }}"
                                    hx-vals='{"quantity": "{{ $item->quantity + 1 }}"}'
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
            <span>{{ $shippingCost === 0 ? 'Free' : '৳' . number_format($shippingCost) }}</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span>৳{{ number_format($total) }}</span>
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
                <label for="shipping_address">Delivery Address *</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="2" placeholder="House no, Flat, Street, Area" required></textarea>
            </div>

            <div class="form-group">
                <label for="shipping_city">City *</label>
                <select id="shipping_city" name="shipping_city" class="form-control" required>
                    <option value="" disabled selected>Select City</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Chittagong">Chittagong</option>
                    <option value="Sylhet">Sylhet</option>
                    <option value="Rajshahi">Rajshahi</option>
                    <option value="Khulna">Khulna</option>
                    <option value="Barisal">Barisal</option>
                    <option value="Rangpur">Rangpur</option>
                    <option value="Mymensingh">Mymensingh</option>
                </select>
            </div>

            <div class="form-group">
                <label>Payment Method *</label>
                <div class="payment-methods-grid">
                    <label class="payment-option active">
                        <input type="radio" name="payment_method" value="cod" checked required>
                        <div class="payment-option-content">
                            <span class="method-title">Cash on Delivery</span>
                            <span class="method-desc">Pay when you receive the product</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bkash">
                        <div class="payment-option-content">
                            <span class="method-title">bKash</span>
                            <span class="method-desc">Pay online securely</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="nagad">
                        <div class="payment-option-content">
                            <span class="method-title">Nagad</span>
                            <span class="method-desc">Fast mobile wallet payment</span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full checkout-submit-btn">
                <span>Place Order (৳{{ number_format($total) }})</span>
                <div id="checkout-indicator" class="htmx-indicator spinner-sm"></div>
            </button>
        </form>
    </div>
@endif
