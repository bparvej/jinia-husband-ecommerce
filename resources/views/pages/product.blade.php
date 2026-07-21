@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="product-detail-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Home</a>
            <span class="breadcrumb-sep">/</span>
            @if ($product->category)
            <a href="/category/{{ $product->category->slug }}">{{ $product->category->name }}</a>
            <span class="breadcrumb-sep">/</span>
            @endif
            <span class="breadcrumb-current">{{ $product->name }}</span>
        </div>

        <div class="product-detail-layout">
            <div class="product-gallery">
                <div class="product-main-image" id="product-main-image">
                    <img src="{{ $images[0] ?? '/assets/images/category-bookshelf.png' }}"
                         alt="{{ $product->name }}"
                         id="main-product-img"
                         data-zoom="{{ $images[0] ?? '/assets/images/category-bookshelf.png' }}">
                    @if ($product->badge)
                    <span class="badge badge-{{ str_replace(' ', '-', strtolower($product->badge)) === 'best-seller' ? 'hot' : strtolower($product->badge) }}">{{ $product->badge }}</span>
                    @endif
                    <div class="zoom-lens" id="zoom-lens"></div>
                </div>
                @if (count($images) > 1)
                <div class="product-thumbnails" id="product-thumbnails">
                    @foreach ($images as $i => $img)
                    <button class="thumb-btn {{ $i === 0 ? 'active' : '' }}"
                            data-img="{{ $img }}"
                            onclick="switchImage(this)">
                        <img src="{{ $img }}" alt="{{ $product->name }} thumbnail {{ $i + 1 }}">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="product-info-panel">
                <span class="product-category">
                    {{ $product->category ? $product->category->name : '' }}
                </span>
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-meta">
                    <div class="product-rating">
                        <div class="stars">
                            @for ($s = 0; $s < round($product->avg_rating ?? 0); $s++)<span>★</span>@endfor
                            @for ($s = round($product->avg_rating ?? 0); $s < 5; $s++)<span>☆</span>@endfor
                        </div>
                        <span class="review-count">({{ $product->review_count ?? 0 }} reviews)</span>
                    </div>
                    <span class="sold-count">{{ $product->sold_count ?? 0 }} sold</span>
                </div>

                <div class="product-price">
                    <span class="price-current">৳{{ number_format($product->price) }}</span>
                    @if ($product->compare_price)
                    <span class="price-old">৳{{ number_format($product->compare_price) }}</span>
                    <span class="discount">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                    @endif
                </div>

                @if ($product->short_description)
                <p class="product-short-desc">{{ $product->short_description }}</p>
                @endif

                <div class="product-actions-detail">
                    <div class="qty-selector-detail">
                        <button class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="number" id="detail-qty" value="1" min="1" max="99" readonly>
                        <button class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>

                    <button class="btn btn-primary btn-lg add-to-cart-detail-btn" onclick="addToCartDetail(event)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        Add to Cart
                    </button>

                    <a href="/buy/{{ $product->slug }}" class="btn btn-accent btn-lg buy-now-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Buy Now
                    </a>
                </div>

                <div class="product-trust">
                    <div class="trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Secure checkout</span>
                    </div>
                    <div class="trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <span>Free delivery over ৳5,000</span>
                    </div>
                    <div class="trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        <span>14-day easy returns</span>
                    </div>
                </div>

                @if ($product->description)
                <div class="product-description">
                    <h3>Description</h3>
                    <div class="description-content">
                        {{ $product->description }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if ($relatedProducts && count($relatedProducts) > 0)
<section class="products related-products">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">You May Also Like</span>
            <h2 class="section-title">Related Products</h2>
        </div>
        <div class="product-grid">
            @foreach ($relatedProducts as $rp)
            <div class="product-card">
                <div class="product-image">
                    <a href="/product/{{ $rp->slug }}">
                        <img src="{{ $rp->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $rp->name }}" loading="lazy">
                    </a>
                    @if ($rp->badge)
                    <div class="product-badges">
                        <span class="badge badge-{{ str_replace(' ', '-', strtolower($rp->badge)) === 'best-seller' ? 'hot' : strtolower($rp->badge) }}">{{ $rp->badge }}</span>
                    </div>
                    @endif
                    <div class="product-actions">
                        <button class="action-btn" aria-label="Add to wishlist">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>
                    <button class="add-to-cart-btn"
                        hx-post="/cart/add"
                        hx-vals='{"product_id": "{{ $rp->id }}", "quantity": "1"}'
                        hx-target="#cart-drawer-body"
                    >Add to Cart</button>
                </div>
                <div class="product-info">
                    <span class="product-category">{{ $rp->category ? $rp->category->name : '' }}</span>
                    <h3 class="product-name"><a href="/product/{{ $rp->slug }}">{{ $rp->name }}</a></h3>
                    <div class="product-rating">
                        <div class="stars">
                            @for ($s = 0; $s < round($rp->avg_rating ?? 0); $s++)<span>★</span>@endfor
                            @for ($s = round($rp->avg_rating ?? 0); $s < 5; $s++)<span>☆</span>@endfor
                        </div>
                        <span class="review-count">({{ $rp->review_count ?? 0 }})</span>
                    </div>
                    <div class="product-price">
                        <span class="price-current">৳{{ number_format($rp->price) }}</span>
                        @if ($rp->compare_price)
                        <span class="price-old">৳{{ number_format($rp->compare_price) }}</span>
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

<script>
let qty = document.getElementById('detail-qty');
let cartBody = document.getElementById('cart-drawer-body');

function changeQty(delta) {
    let val = parseInt(qty.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    qty.value = val;
}

function addToCartDetail(e) {
    e.preventDefault();
    let btn = e.currentTarget;
    let q = parseInt(qty.value);
    let productId = {{ $product->id }};
    let formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', q);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.text())
    .then(html => {
        if (cartBody) cartBody.innerHTML = html;
        let cartDrawer = document.getElementById('cart-drawer');
        let cartBackdrop = document.getElementById('cart-drawer-backdrop');
        if (cartDrawer) cartDrawer.classList.add('active');
        if (cartBackdrop) cartBackdrop.classList.add('active');
        document.body.style.overflow = 'hidden';

        let countEl = document.getElementById('cart-count');
        if (countEl) {
            let newCount = parseInt(countEl.textContent || '0') + q;
            countEl.textContent = newCount;
        }

        btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg> Added!';
        btn.style.background = 'var(--clr-success)';
        setTimeout(() => {
            btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg> Add to Cart';
            btn.style.background = '';
        }, 2000);
    })
    .catch(err => console.error('Cart add failed:', err));
}

let mainImg = document.getElementById('main-product-img');
let imgContainer = document.getElementById('product-main-image');
let lens = document.getElementById('zoom-lens');

if (mainImg && imgContainer && window.innerWidth > 768) {
    imgContainer.addEventListener('mousemove', function(e) {
        let rect = this.getBoundingClientRect();
        let x = ((e.clientX - rect.left) / rect.width) * 100;
        let y = ((e.clientY - rect.top) / rect.height) * 100;
        mainImg.style.transformOrigin = x + '% ' + y + '%';
        mainImg.style.transform = 'scale(2)';
        mainImg.style.transition = 'none';
        if (lens) {
            lens.style.display = 'block';
            lens.style.left = (e.clientX - rect.left - 60) + 'px';
            lens.style.top = (e.clientY - rect.top - 60) + 'px';
        }
    });

    imgContainer.addEventListener('mouseleave', function() {
        mainImg.style.transformOrigin = 'center center';
        mainImg.style.transform = 'scale(1)';
        mainImg.style.transition = 'transform 0.3s ease';
        if (lens) lens.style.display = 'none';
    });
}

function switchImage(el) {
    document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    let newSrc = el.dataset.img;
    mainImg.src = newSrc;
    mainImg.dataset.zoom = newSrc;
}
</script>
@endsection