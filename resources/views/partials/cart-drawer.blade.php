<div class="cart-drawer-backdrop" id="cart-drawer-backdrop"></div>
<div class="cart-drawer" id="cart-drawer">
    <div class="cart-drawer-header">
        <div class="header-title-container">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <h3>Shopping Cart</h3>
        </div>
        <button class="cart-drawer-close" id="cart-drawer-close" aria-label="Close cart">&times;</button>
    </div>

    <div class="cart-drawer-body" id="cart-drawer-body"
         hx-get="/cart/drawer"
         hx-trigger="load"
         hx-swap="innerHTML">
        <div class="cart-loader-container">
            <div class="cart-spinner"></div>
            <p>Loading your cart...</p>
        </div>
    </div>
</div>
