@extends('layouts.main')

@section('content')
@include('partials.header')

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
