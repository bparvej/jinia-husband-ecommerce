@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="order-success-section">
    <div class="container">
        <div class="order-success-card">
            <div class="success-icon">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h1>Order Confirmed!</h1>
            @if ($order)
            <p class="success-order-number">Order #{{ $order->order_number }}</p>
            <p class="success-message">
                Thank you for shopping with HomeI. We have received your order and will contact you shortly at
                <strong>{{ $order->shipping_phone }}</strong> to confirm delivery.
            </p>
            @else
            <p class="success-message">Thank you for shopping with HomeI. We have received your order and will contact you shortly to confirm delivery.</p>
            @endif

            <div class="success-contact">
                <span>Need help? Call or WhatsApp</span>
                <a href="tel:+8801787656135" class="contact-link">+880 1787656135</a>
                <span>or email</span>
                <a href="mailto:homeibd26@gmail.com" class="contact-link">homeibd26@gmail.com</a>
            </div>

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
                        <img src="{{ $product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}" loading="lazy">
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

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
