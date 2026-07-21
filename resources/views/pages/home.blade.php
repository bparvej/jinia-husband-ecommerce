@extends('layouts.main')

@section('content')
<div class="announcement-bar" id="announcement-bar">
    <div class="container">
        <p>Free Delivery on orders above ৳5,000 — <a href="#shop">Shop Now</a></p>
    </div>
    <button class="announcement-close" id="announcement-close" aria-label="Close announcement">&times;</button>
</div>

@include('partials.header', ['activePage' => 'home'])

<section class="hero" id="hero">
    <div class="hero-bg">
        <img src="/assets/images/hero-living-room.png" alt="Cozy wooden living room by HomeI" loading="eager">
        <div class="hero-overlay"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-badge">New Collection 2026</div>
        <h1 class="hero-title">Crafted for <br><em>Cozy Living</em></h1>
        <p class="hero-subtitle">Handcrafted wooden furniture & home décor that brings warmth, style, and soul to every corner of your home.</p>
        <div class="hero-cta">
            <a href="#categories" class="btn btn-primary" id="hero-shop-btn">Explore Collection</a>
            <a href="/our-story" class="btn btn-outline" id="hero-about-btn">Our Story</a>
        </div>
        <div class="hero-stats">
            <div class="stat">
                <span class="stat-number" data-count="{{ isset($products) ? count($products) * 60 : 500 }}">0</span><span class="stat-suffix">+</span>
                <span class="stat-label">Products</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-number" data-count="2000">0</span><span class="stat-suffix">+</span>
                <span class="stat-label">Happy Homes</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-number" data-count="4">0</span><span class="stat-suffix">.9★</span>
                <span class="stat-label">Avg Rating</span>
            </div>
        </div>
    </div>
    <div class="hero-scroll-hint">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<section class="features" id="features">
    <div class="container features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3>Premium Quality</h3>
            <p>Handcrafted from selected hardwoods, built to last generations.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <h3>Fast Delivery</h3>
            <p>Free delivery across Dhaka within 3 days. Nationwide in 5-7 days.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3>Expert Support</h3>
            <p>Design consultation & after-sales support from our in-house team.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <h3>Easy Returns</h3>
            <p>14-day hassle-free return policy on all products.</p>
        </div>
    </div>
</section>

<section class="categories" id="categories">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Browse</span>
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-desc">Find the perfect piece for every room in your home</p>
        </div>
        <div class="category-grid">
            @if (isset($categories) && count($categories) > 0)
                @foreach ($categories as $i => $cat)
                <a href="/category/{{ $cat->slug }}" class="category-card {{ $i === 0 || $i === 4 ? 'category-wide' : '' }}">
                    <img src="{{ $cat->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $cat->name }}" loading="lazy">
                    <div class="category-info">
                        <h3>{{ $cat->name }}</h3>
                        <span class="category-count">Shop Now</span>
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

<section class="products featured-products" id="best-sellers">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Premium Picks</span>
            <h2 class="section-title">Best Sellers</h2>
            <p class="section-desc">Our most loved handcrafted pieces, now available for your home</p>
        </div>
        <div class="product-grid">
            @if (isset($featuredProducts) && count($featuredProducts) > 0)
                @foreach ($featuredProducts as $product)
                <div class="product-card featured" data-category="{{ $product->category ? $product->category->slug : 'all' }}">
                    <div class="product-image">
                        <a href="/product/{{ $product->slug }}">
                            <img src="{{ $product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}" loading="lazy">
                        </a>
                        @if ($product->badge)
                        <div class="product-badges">
                            <span class="badge badge-{{ str_replace(' ', '-', strtolower($product->badge)) === 'best-seller' ? 'hot' : strtolower($product->badge) }}">{{ $product->badge }}</span>
                        </div>
                        @endif
                        <div class="product-actions">
                            <button class="action-btn" aria-label="Add to wishlist">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                            <button class="action-btn" aria-label="Quick view">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
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
                            <span class="discount">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state text-center" style="grid-column: 1/-1; padding: 3rem;">
                    <p class="text-muted">No featured products found.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="products" id="new-arrivals">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Just Landed</span>
            <h2 class="section-title">New Arrivals</h2>
            <p class="section-desc">Fresh designs crafted for the modern Bangladeshi home</p>
        </div>
        <div class="product-grid" id="product-grid">
            @if (isset($products) && count($products) > 0)
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
                        <div class="product-actions">
                            <button class="action-btn" aria-label="Add to wishlist">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                            <button class="action-btn" aria-label="Quick view">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <button class="add-to-cart-btn"
                            hx-post="/cart/add"
                            hx-vals='{"product_id": "{{ $product->id }}", "quantity": "1"}'
                            hx-target="#cart-drawer-body"
                        >Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <span class="product-category">{{ $product->category ? $product->category->name : '' }}</span>
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
                            <span class="discount">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <div class="section-footer">
            <a href="#" class="btn btn-secondary" id="view-all-btn">View All Products →</a>
        </div>
    </div>
</section>

<section class="newsletter" id="newsletter">
    <div class="container newsletter-inner">
        <div class="newsletter-content">
            <h2>Stay in the Loop</h2>
            <p>Subscribe for exclusive deals, new arrivals, and design inspiration delivered straight to your inbox.</p>
        </div>
        <form class="newsletter-form" id="newsletter-form">
            <input type="email" placeholder="Enter your email address" required id="newsletter-email">
            <button type="submit" class="btn btn-primary" id="newsletter-submit">Subscribe</button>
        </form>
    </div>
</section>

@include('partials.footer')

<button class="back-to-top" id="back-to-top" aria-label="Back to top">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<a href="https://wa.me/880" target="_blank" rel="noopener" class="whatsapp-float" id="whatsapp-float" aria-label="Chat on WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

@include('partials.cart-drawer')
@endsection
