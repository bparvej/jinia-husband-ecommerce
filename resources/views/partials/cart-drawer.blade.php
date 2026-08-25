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

<script>
(function() {
    function getCartSubtotal() {
        var el = document.getElementById('cart-subtotal');
        return el ? parseFloat(el.value) : 0;
    }

    function applyCartShipping() {
        var subtotal = getCartSubtotal();
        var freeShipping = subtotal >= 5000;
        var sel = document.getElementById('shipping_city');
        var shippingLabel = document.getElementById('cart-shipping-label');
        var totalLabel = document.getElementById('cart-total-label');
        var btnTotal = document.getElementById('cart-btn-total');
        if (!sel) return;

        var optDhaka = sel.querySelector('option[value="Dhaka"]');
        var optOutside = sel.querySelector('option[value="Outside Dhaka"]');

        if (freeShipping) {
            if (optDhaka) { optDhaka.disabled = false; optDhaka.text = 'Inside Dhaka — Free'; }
            if (optOutside) { optOutside.disabled = false; optOutside.text = 'Outside Dhaka — Free'; }
            if (!sel.value || sel.value === '') { sel.value = 'Dhaka'; }
        } else {
            if (optDhaka) optDhaka.text = 'Inside Dhaka — ৳80';
            if (optOutside) optOutside.text = 'Outside Dhaka — ৳120';
            if (optDhaka) optDhaka.disabled = false;
            if (optOutside) optOutside.disabled = false;
        }

        var shipping = 0;
        if (!freeShipping && sel.value) {
            shipping = sel.value === 'Dhaka' ? 80 : 120;
        }
        var total = subtotal + shipping;

        if (shippingLabel) shippingLabel.textContent = shipping === 0 ? 'Free' : '৳' + shipping.toLocaleString('en-US');
        if (totalLabel) totalLabel.textContent = '৳' + total.toLocaleString('en-US');
        if (btnTotal) btnTotal.textContent = '৳' + total.toLocaleString('en-US');
    }

    window.updateCartShipping = applyCartShipping;

    document.addEventListener('htmx:afterSwap', function(e) {
        if (e.detail.target && e.detail.target.id === 'cart-drawer-body') {
            setTimeout(applyCartShipping, 50);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(applyCartShipping, 200);
    });
})();
</script>
