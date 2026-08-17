@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="quick-checkout-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Home</a>
            <span class="breadcrumb-sep">/</span>
            <a href="/product/{{ $product->slug }}">{{ $product->name }}</a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">Checkout</span>
        </div>

        <div class="checkout-layout">
            <div class="checkout-product-summary">
                <div class="checkout-product-card">
                    <div class="checkout-product-image">
                        <img src="{{ $product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}">
                    </div>
                    <div class="checkout-product-info">
                        <span class="checkout-product-category">{{ $product->category ? $product->category->name : '' }}</span>
                        <h2 class="checkout-product-name">{{ $product->name }}</h2>
                        <div class="checkout-product-rating">
                            <div class="stars">
                                @for ($s = 0; $s < round($product->avg_rating ?? 0); $s++)<span>★</span>@endfor
                                @for ($s = round($product->avg_rating ?? 0); $s < 5; $s++)<span>☆</span>@endfor
                            </div>
                            <span>({{ $product->review_count ?? 0 }} reviews)</span>
                        </div>
                        <div class="checkout-product-price">৳{{ number_format($product->price) }}</div>
                        @if ($product->short_description)
                        <p class="checkout-product-desc">{{ $product->short_description }}</p>
                        @endif
                    </div>
                </div>

                <div class="checkout-order-summary">
                    <h3>Order Summary</h3>
                    <div class="co-summary-row">
                        <span>Price (1 item)</span>
                        <span>৳{{ number_format($product->price) }}</span>
                    </div>
                    <div class="co-summary-row">
                        <span>Shipping</span>
                        <span class="co-shipping-value">Calculated at order</span>
                    </div>
                    <div class="co-summary-row co-total">
                        <span>Total</span>
                        <span>৳{{ number_format($product->price) }}</span>
                    </div>
                </div>

                <div class="checkout-trust">
                    <div class="trust-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Secure checkout via SSL</span>
                    </div>
                    <div class="trust-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Order support available</span>
                    </div>
                </div>
            </div>

            <div class="checkout-form-section">
                <div class="checkout-form-header">
                    <h2>Complete Your Order</h2>
                    <p>Fill in your details to place the order. No registration required.</p>
                </div>

                <form id="quick-checkout-form" hx-post="/quick-checkout" hx-target="#quick-checkout-form" hx-indicator="#qc-indicator">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1" id="checkout-qty">

                    <div class="checkout-qty-selector">
                        <label>Quantity</label>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="changeCheckoutQty(-1)">−</button>
                            <span class="qty-val" id="checkout-qty-display">1</span>
                            <button type="button" class="qty-btn" onclick="changeCheckoutQty(1)">+</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="qc_name">Full Name *</label>
                        <input type="text" id="qc_name" name="shipping_name" class="form-control" placeholder="E.g. Rahman Ali" required>
                    </div>

                    <div class="form-group">
                        <label for="qc_phone">Phone Number *</label>
                        <input type="tel" id="qc_phone" name="shipping_phone" class="form-control" placeholder="E.g. 01712345678" required>
                    </div>

                    <div class="form-group">
                        <label for="qc_email">Email (optional)</label>
                        <input type="email" id="qc_email" name="shipping_email" class="form-control" placeholder="For order updates via email">
                    </div>

                    <div class="form-group">
                        <label for="qc_address">Delivery Address *</label>
                        <textarea id="qc_address" name="shipping_address" class="form-control" rows="2" placeholder="House no, Flat, Street, Area" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="qc_city">City *</label>
                        <select id="qc_city" name="shipping_city" class="form-control" required>
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
                        <label for="qc_notes">Order Notes (optional)</label>
                        <textarea id="qc_notes" name="notes" class="form-control" rows="1" placeholder="Any special instructions"></textarea>
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

                    <button type="submit" class="btn btn-primary btn-full btn-lg place-order-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Place Order — ৳<span id="checkout-total">{{ number_format($product->price) }}</span></span>
                        <div id="qc-indicator" class="htmx-indicator spinner-sm"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')

<script>
let checkoutQty = 1;

function changeCheckoutQty(delta) {
    let val = checkoutQty + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    checkoutQty = val;
    document.getElementById('checkout-qty-display').textContent = val;
    document.getElementById('checkout-qty').value = val;
    let unitPrice = {{ $product->price }};
    document.getElementById('checkout-total').textContent = (unitPrice * val).toLocaleString('en-US');
}
</script>
@endsection