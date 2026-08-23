@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="category-page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Home</a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">{{ $category->name }}</span>
        </div>
        <div class="category-hero">
            <div class="category-hero-content">
                <span class="section-tag">{{ $category->name }}</span>
                <h1 class="category-title">{{ $category->name }}</h1>
                @if ($category->description)
                <p class="category-desc">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="products category-products">
    <div class="container">
        @if ($products->count() > 0)
        <div class="product-grid" id="product-grid">
            @foreach ($products as $product)
            <div class="product-card" data-category="{{ $product->category ? $product->category->slug : 'all' }}">
                <div class="product-image">
                    <a href="/product/{{ $product->slug }}">
                        <img src="{{ $product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null;this.src='/assets/images/category-bookshelf.png';">
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
        </div>

        <div class="pagination-container">
            @if ($products->hasPages())
            <div class="pagination">
                @if ($products->onFirstPage())
                    <span class="page-btn disabled">← Prev</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="page-btn"
                       hx-get="{{ $products->previousPageUrl() }}" hx-target="#product-grid" hx-swap="outerHTML">← Prev</a>
                @endif

                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="page-btn {{ $page === $products->currentPage() ? 'active' : '' }}"
                       hx-get="{{ $url }}" hx-target="#product-grid" hx-swap="outerHTML">{{ $page }}</a>
                @endforeach

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="page-btn"
                       hx-get="{{ $products->nextPageUrl() }}" hx-target="#product-grid" hx-swap="outerHTML">Next →</a>
                @else
                    <span class="page-btn disabled">Next →</span>
                @endif
            </div>
            @endif
        </div>
        @else
        <div class="empty-state text-center" style="padding: 4rem 0;">
            <div class="empty-icon" style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
            <h3>No products found</h3>
            <p class="text-muted">This category doesn't have any products yet. Check back soon!</p>
            <a href="/" class="btn btn-primary" style="margin-top: 1rem;">Back to Home</a>
        </div>
        @endif
    </div>
</section>

@include('partials.footer')

@include('partials.cart-drawer')
@endsection