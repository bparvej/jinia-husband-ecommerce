@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="order-success-section">
    <div class="container">
        <div class="order-success-card">

            <!-- Animated Checkmark -->
            <div class="success-icon-wrapper">
                <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <h1>Order Confirmed!</h1>

            @if ($order)
            <p class="success-order-number">Order #{{ $order->order_number }}</p>
            <p class="success-message">
                Thank you for shopping with HomeI! We've received your order and will contact you shortly at
                <strong>{{ $order->shipping_phone }}</strong> to confirm delivery details.
            </p>

            <!-- Order Summary Card -->
            <div class="order-summary-box">
                <h4>Order Summary</h4>
                <div class="summary-details">
                    @if($order->items && count($order->items) > 0)
                    <div class="summary-items">
                        @foreach($order->items as $item)
                        <div class="summary-item">
                            <div class="summary-item-info">
                                <span class="summary-item-name">{{ $item->product_name }}</span>
                                <span class="summary-item-qty">x{{ $item->quantity }}</span>
                            </div>
                            <span class="summary-item-price">৳{{ number_format($item->total_price) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="summary-divider"></div>
                    @endif

                    <div class="summary-row"><span>Subtotal</span><span>৳{{ number_format($order->subtotal) }}</span></div>
                    <div class="summary-row"><span>Shipping</span><span>{{ $order->shipping_cost > 0 ? '৳'.number_format($order->shipping_cost) : 'Free' }}</span></div>
                    <div class="summary-row summary-total"><span>Total</span><span>৳{{ number_format($order->total) }}</span></div>

                    <div class="summary-divider"></div>

                    <div class="summary-row"><span>Name</span><span>{{ $order->shipping_name }}</span></div>
                    <div class="summary-row"><span>Phone</span><span>{{ $order->shipping_phone }}</span></div>
                    <div class="summary-row"><span>Address</span><span>{{ $order->shipping_address }}, {{ $order->shipping_city }}</span></div>
                    <div class="summary-row">
                        <span>Payment</span>
                        <span>
                            @if ($order->payment && $order->payment->method === 'cod')
                                Cash on Delivery
                            @elseif ($order->payment)
                                {{ ucfirst($order->payment->method) }}
                            @else
                                Cash on Delivery
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @else
            <p class="success-message">Thank you for shopping with HomeI! We've received your order and will contact you shortly to confirm delivery details.</p>
            @endif

            <!-- Contact Actions -->
            <div class="success-contact-section">
                <p class="contact-heading">Need help with your order?</p>
                <div class="contact-buttons">
                    <a href="https://wa.me/8801787656135?text=Hi!%20I%20need%20help%20with%20my%20order%20{{ $order->order_number ?? '' }}" target="_blank" rel="noopener" class="contact-btn whatsapp-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat on WhatsApp
                    </a>
                    <a href="tel:+8801787656135" class="contact-btn call-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Call Us
                    </a>
                    <a href="mailto:homeibd26@gmail.com" class="contact-btn email-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Email Us
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="success-actions">
                <a href="/" class="btn btn-primary">Continue Shopping</a>
                <a href="/contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

@if (isset($products) && count($products) > 0)
<section class="products">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">You May Also Like</span>
            <h2 class="section-title">Complete Your Home</h2>
            <p class="section-desc">Explore more handcrafted pieces our customers love</p>
        </div>
        <div class="product-grid">
            @foreach ($products as $product)
            <div class="product-card" data-category="{{ $product->category ? $product->category->slug : 'all' }}">
                <div class="product-image">
                    <a href="/product/{{ $product->slug }}">
                        <img src="{{ !empty($product->image) ? asset('storage/' . $product->image) : '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null;this.src='/assets/images/category-bookshelf.png';">
                    </a>
                    @if ($product->badge)
                    <div class="product-badges">
                        <span class="badge badge-{{ str_replace(' ', '-', strtolower($product->badge)) === 'best-seller' ? 'hot' : strtolower($product->badge) }}">{{ $product->badge }}</span>
                    </div>
                    @endif
                    <button class="add-to-cart-btn"
                        hx-post="/cart/add"
                        hx-vals='{"product_id": "{{ $product->id }}", "quantity": "1"}'
                        hx-target="#cart-drawer-body"
                    >Add to Cart</button>
                </div>
                <div class="product-info">
                    <span class="product-category text-accent">{{ $product->category ? $product->category->name : 'Furniture' }}</span>
                    <h3 class="product-name"><a href="/product/{{ $product->slug }}">{{ $product->name }}</a></h3>
                    <div class="product-rating">
                        <div class="stars">
                            @for ($s = 0; $s < round($product->avg_rating ?? 0); $s++)<span>★</span>@endfor
                            @for ($s = round($product->avg_rating ?? 0); $s < 5; $s++)<span>☆</span>@endfor
                        </div>
                        <span class="review-count">({{ $product->review_count ?? 0 }})</span>
                    </div>
                    <div class="product-price">
                        <span class="price-current">৳{{ number_format($product->price) }}</span>
                        @if ($product->compare_price)
                        <span class="price-old">৳{{ number_format($product->compare_price) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
.success-icon-wrapper { margin-bottom: 1.5rem; }
.checkmark-svg { width: 72px; height: 72px; border-radius: 50%; display: block; stroke-width: 2; stroke: #22c55e; stroke-miterlimit: 10; box-shadow: inset 0px 0px 0px #22c55e; animation: csFill .4s ease-in-out .4s forwards, csScale .3s ease-in-out .9s both; margin: 0 auto; }
.checkmark-circle { stroke-dasharray: 166; stroke-dashoffset: 166; stroke-width: 2; stroke-miterlimit: 10; stroke: #22c55e; fill: none; animation: csStroke .6s cubic-bezier(0.65,0,0.45,1) forwards; }
.checkmark-check { transform-origin: 50% 50%; stroke-dasharray: 48; stroke-dashoffset: 48; animation: csStroke .3s cubic-bezier(0.65,0,0.45,1) .8s forwards; }
@keyframes csStroke { 100% { stroke-dashoffset: 0; } }
@keyframes csScale { 0%,100% { transform: none; } 50% { transform: scale3d(1.1,1.1,1); } }
@keyframes csFill { 100% { box-shadow: inset 0px 0px 0px 30px rgba(34,197,94,0.1); } }

.order-success-card h1 { font-family: var(--font-heading); font-size: 1.8rem; color: var(--clr-primary-dark); margin-bottom: 0.25rem; }
.success-order-number { font-size: 1rem; font-weight: 700; color: var(--clr-accent-dark); margin-bottom: 0.5rem; font-family: monospace; letter-spacing: 1px; }

.order-summary-box { background: var(--clr-bg-warm); border: 1px solid var(--clr-bg-muted); border-radius: var(--radius-md); padding: 1.25rem; text-align: left; margin: 1.5rem 0; }
.order-summary-box h4 { font-size: 0.9rem; font-weight: 700; color: var(--clr-primary); margin: 0 0 0.75rem; border-bottom: 1px solid rgba(92,61,46,0.12); padding-bottom: 8px; }
.summary-items { display: flex; flex-direction: column; gap: 8px; }
.summary-item { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: var(--clr-text-secondary); }
.summary-item-info { display: flex; align-items: center; gap: 8px; }
.summary-item-name { font-weight: 500; color: var(--clr-text); }
.summary-item-qty { color: #999; font-size: 0.8rem; }
.summary-item-price { font-weight: 600; color: var(--clr-text); }
.summary-divider { border: none; border-top: 1px dashed rgba(92,61,46,0.12); margin: 10px 0; }
.summary-row { display: flex; justify-content: space-between; font-size: 0.82rem; color: var(--clr-text-secondary); padding: 3px 0; }
.summary-row.summary-total { font-weight: 700; font-size: 0.95rem; color: var(--clr-primary-dark); padding-top: 8px; border-top: 2px solid var(--clr-primary); margin-top: 4px; }

.success-contact-section { margin: 1.5rem 0; }
.contact-heading { font-size: 0.9rem; color: var(--clr-text-secondary); margin-bottom: 1rem; }
.contact-buttons { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
.contact-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.whatsapp-btn { background: #25d366; color: #fff; }
.whatsapp-btn:hover { background: #1da851; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,211,102,0.3); }
.call-btn { background: var(--clr-primary); color: #fff; }
.call-btn:hover { background: var(--clr-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(92,61,46,0.2); }
.email-btn { background: #f3f4f6; color: var(--clr-text); border: 1px solid #d1d5db; }
.email-btn:hover { background: #e5e7eb; transform: translateY(-1px); }

.success-actions { display: flex; gap: 12px; justify-content: center; margin-top: 1.5rem; }
.success-actions .btn { min-width: 160px; text-align: center; }

@media (max-width: 480px) {
    .contact-buttons { flex-direction: column; }
    .contact-btn { justify-content: center; }
    .success-actions { flex-direction: column; }
    .success-actions .btn { width: 100%; }
}
</style>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
