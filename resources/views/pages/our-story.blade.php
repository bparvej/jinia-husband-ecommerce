@extends('layouts.main')

@section('content')
<header class="header" id="header">
    <div class="container header-inner">
        <a href="/" class="logo" id="logo">
            <span class="logo-text">HOMEI</span>
            <span class="logo-tagline">Cozy Living</span>
        </a>
        <nav class="nav" id="main-nav">
            <ul class="nav-list">
                <li><a href="/" class="nav-link">Home</a></li>
                <li class="has-dropdown">
                    <a href="/#categories" class="nav-link">Shop <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
                    <div class="dropdown" id="shop-dropdown">
                        @foreach ($categories as $cat)
                        <a href="/category/{{ $cat->slug }}" class="dropdown-link">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </li>
                <li><a href="/#new-arrivals" class="nav-link">New Arrivals</a></li>
                <li><a href="/#best-sellers" class="nav-link">Best Sellers</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <button class="icon-btn cart-btn" id="cart-btn" aria-label="Cart">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span class="cart-count" id="cart-count">0</span>
            </button>
            @auth
            <a href="{{ auth()->user()->role && in_array(auth()->user()->role->name, ['super_admin', 'admin', 'manager']) ? '/admin/dashboard' : '#' }}" class="icon-btn user-btn" aria-label="Account">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            @else
            <a href="/login" class="icon-btn user-btn" aria-label="Login">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            @endauth
        </div>
    </div>
</header>

<section class="page-hero page-hero-story">
    <div class="container">
        <span class="section-tag">Our Story</span>
        <h1 class="page-hero-title">The HomeI Journey</h1>
        <p class="page-hero-desc">From a small workshop in Dhaka to homes across Bangladesh.</p>
    </div>
</section>

<section class="page-content">
    <div class="container container-narrow">
        <div class="content-block">
            <h2>How It All Began</h2>
            <p>HomeI started with a simple idea: every Bangladeshi home deserves furniture that is beautiful, affordable, and built to last. What began as a small woodworking workshop in Dhaka has grown into a trusted name for handcrafted furniture and home décor.</p>
            <p>Our founders noticed a gap in the market — mass-produced furniture that didn't last, or imported pieces that were too expensive for most families. They set out to create something different: locally crafted furniture using sustainable wood, combining traditional Bangladeshi woodworking skills with modern design sensibilities.</p>
        </div>

        <div class="content-block">
            <h2>Our Craft</h2>
            <p>Every HomeI piece is made by skilled artisans who bring decades of woodworking expertise to their craft. We use solid wood — mango, sheesham, oak, and other sustainably sourced hardwoods — because we believe furniture should be built to last generations, not just seasons.</p>
            <p>From the initial design sketches to the final finish, each piece goes through careful quality checks. We take pride in the details: smooth joints, even finishes, and sturdy construction that you can feel the moment you touch it.</p>
        </div>

        <div class="content-block">
            <h2>What Drives Us</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Quality Over Quantity</h3>
                        <p>We'd rather make fewer pieces, each one excellent, than flood the market with mediocrity.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Local Craftsmanship</h3>
                        <p>We support Bangladeshi artisans and invest in preserving traditional woodworking techniques.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Sustainable Practices</h3>
                        <p>We use responsibly sourced wood and eco-friendly finishes because we care about the planet our children will inherit.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Accessible Design</h3>
                        <p>Beautiful furniture shouldn't be a luxury. We work hard to keep our prices fair without compromising on quality.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-block">
            <h2>Today & Beyond</h2>
            <p>Today, HomeI serves customers across Bangladesh, delivering carefully packaged furniture right to their doorstep. We're constantly expanding our collection — from bookshelves and dining sets to bedroom furniture and decorative accents.</p>
            <p>But no matter how much we grow, we'll never lose sight of what got us here: a love for beautiful craftsmanship and a commitment to making every house feel like home.</p>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
