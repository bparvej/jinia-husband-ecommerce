/* ============================================
   HOMEI — Cozy Living | Interactive JS
   Dynamic-ready ecommerce template
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ========================
    // ANNOUNCEMENT BAR
    // ========================
    const announcementBar = document.getElementById('announcement-bar');
    const announcementClose = document.getElementById('announcement-close');

    if (announcementClose) {
        announcementClose.addEventListener('click', () => {
            announcementBar.classList.add('hidden');
        });
    }

    // ========================
    // HEADER SCROLL EFFECT
    // ========================
    const header = document.getElementById('header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // ========================
    // MOBILE MENU
    // ========================
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('main-nav');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            nav.classList.toggle('open');
            document.body.style.overflow = nav.classList.contains('open') ? 'hidden' : '';
        });

        // Close on link click
        nav.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                nav.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    }

    // ========================
    // SEARCH OVERLAY
    // ========================
    const searchBtn = document.getElementById('search-btn');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    const searchInput = document.getElementById('search-input');

    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            searchOverlay.classList.add('active');
            setTimeout(() => searchInput.focus(), 300);
        });
    }

    if (searchClose) {
        searchClose.addEventListener('click', () => {
            searchOverlay.classList.remove('active');
        });
    }

    // Close search on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            searchOverlay.classList.remove('active');
        }
    });

    // ========================
    // PRODUCT FILTER TABS
    // ========================
    const filterTabs = document.querySelectorAll('.filter-tab');
    const productCards = document.querySelectorAll('.product-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;

            productCards.forEach((card, index) => {
                const category = card.dataset.category;
                const shouldShow = filter === 'all' || category === filter;

                if (shouldShow) {
                    card.style.display = '';
                    card.style.animation = `fadeInUp 0.5s ${index * 0.08}s var(--ease-out) both`;
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ========================
    // COUNTDOWN TIMER
    // ========================
    function startCountdown() {
        // Set end date to 7 days from now
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 7);

        const timerDays = document.getElementById('timer-days');
        const timerHours = document.getElementById('timer-hours');
        const timerMins = document.getElementById('timer-mins');
        const timerSecs = document.getElementById('timer-secs');

        function updateTimer() {
            const now = new Date();
            const diff = endDate - now;

            if (diff <= 0) return;

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);

            timerDays.textContent = String(days).padStart(2, '0');
            timerHours.textContent = String(hours).padStart(2, '0');
            timerMins.textContent = String(mins).padStart(2, '0');
            timerSecs.textContent = String(secs).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    }

    startCountdown();

    // ========================
    // HERO STATS COUNTER ANIMATION
    // ========================
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');

        counters.forEach(counter => {
            const target = parseInt(counter.dataset.count);
            const duration = 2000;
            const start = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(eased * target);
                
                counter.textContent = current.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        });
    }

    // ========================
    // TESTIMONIAL SLIDER
    // ========================
    const testimonialTrack = document.getElementById('testimonial-track');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');
    let currentSlide = 0;

    function getVisibleCards() {
        const width = window.innerWidth;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        return 3;
    }

    function updateSlider() {
        if (!testimonialTrack) return;
        const cards = testimonialTrack.children;
        const visibleCards = getVisibleCards();
        const maxSlide = Math.max(0, cards.length - visibleCards);
        
        if (currentSlide > maxSlide) currentSlide = maxSlide;
        
        const gap = 24; // --space-xl
        const cardWidth = (testimonialTrack.parentElement.offsetWidth - gap * (visibleCards - 1)) / visibleCards;
        const offset = currentSlide * (cardWidth + gap);
        
        testimonialTrack.style.transform = `translateX(-${offset}px)`;
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlider();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const cards = testimonialTrack.children;
            const visibleCards = getVisibleCards();
            const maxSlide = Math.max(0, cards.length - visibleCards);
            
            if (currentSlide < maxSlide) {
                currentSlide++;
                updateSlider();
            }
        });
    }

    window.addEventListener('resize', updateSlider);

    // ========================
    // SCROLL REVEAL ANIMATIONS
    // ========================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -60px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // Trigger counter animation when hero stats come into view
                if (entry.target.closest('.hero')) {
                    animateCounters();
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Add reveal classes to sections
    const revealElements = document.querySelectorAll(
        '.feature-card, .category-card, .product-card, .bestseller-card, ' +
        '.testimonial-card, .about-image, .about-content, .insta-card, ' +
        '.section-header, .promo-text, .promo-image, .newsletter-inner'
    );

    revealElements.forEach((el, index) => {
        el.classList.add('reveal');
        el.style.transitionDelay = `${(index % 6) * 0.1}s`;
        observer.observe(el);
    });

    // ========================
    // BACK TO TOP
    // ========================
    const backToTop = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 600) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ========================
    // CART FUNCTIONALITY (DEMO)
    // ========================
    const cartCount = document.getElementById('cart-count');
    let cartItems = 0;

    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            cartItems++;
            cartCount.textContent = cartItems;
            
            // Cart bounce animation
            cartCount.style.transform = 'scale(1.4)';
            setTimeout(() => {
                cartCount.style.transform = 'scale(1)';
            }, 200);

            // Button feedback
            const originalText = btn.textContent;
            btn.textContent = '✓ Added!';
            btn.style.background = 'var(--clr-success)';
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
            }, 1500);
        });
    });

    // ========================
    // WISHLIST TOGGLE (DEMO)
    // ========================
    document.querySelectorAll('.action-btn[aria-label="Add to wishlist"]').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const svg = btn.querySelector('svg path');
            
            if (btn.classList.contains('active')) {
                svg.setAttribute('fill', '#C44D4D');
                svg.setAttribute('stroke', '#C44D4D');
            } else {
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
            }
        });
    });

    // ========================
    // NEWSLETTER FORM (DEMO)
    // ========================
    const newsletterForm = document.getElementById('newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = document.getElementById('newsletter-email');
            const submitBtn = document.getElementById('newsletter-submit');
            
            submitBtn.textContent = '✓ Subscribed!';
            submitBtn.style.background = 'var(--clr-success)';
            email.value = '';
            
            setTimeout(() => {
                submitBtn.textContent = 'Subscribe';
                submitBtn.style.background = '';
            }, 3000);
        });
    }

    // ========================
    // SMOOTH SCROLL FOR INTERNAL LINKS
    // ========================
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.scrollY - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ========================
    // PARALLAX ON HERO (subtle)
    // ========================
    const heroImg = document.querySelector('.hero-bg img');

    if (heroImg && window.innerWidth > 768) {
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroImg.style.transform = `scale(${1 + scrolled * 0.0003}) translateY(${scrolled * 0.3}px)`;
            }
        });
    }

    // Initial slider setup
    updateSlider();
});
