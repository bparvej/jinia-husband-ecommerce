const cartService = require('./cart.service');

class CartController {
  async getCart(req, res) {
    try {
      req.session.cart = req.session.cart || { items: [] };
      const cart = await cartService.getCart(req.session.userId, req.session.cart);
      res.json({ cart });
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  }

  async getCartDrawer(req, res) {
    try {
      req.session.cart = req.session.cart || { items: [] };
      const cart = await cartService.getCart(req.session.userId, req.session.cart);
      const count = await cartService.getCartCount(req.session.userId, req.session.cart);
      res.render('partials/cart-drawer-content', {
        layout: false,
        cart,
        count
      });
    } catch (err) {
      res.status(500).send(`<div class="toast toast-error">Error loading cart: ${err.message}</div>`);
    }
  }

  async addToCart(req, res) {
    try {
      const { product_id, quantity } = req.body;
      req.session.cart = req.session.cart || { items: [] };
      
      await cartService.addToCart(req.session.userId, req.session.cart, product_id, parseInt(quantity) || 1);
      
      const cart = await cartService.getCart(req.session.userId, req.session.cart);
      const count = await cartService.getCartCount(req.session.userId, req.session.cart);

      // Trigger the custom 'open-cart' event so the frontend slides open the drawer
      res.setHeader('HX-Trigger', 'open-cart');
      
      if (req.headers['hx-request']) {
        // Return updated count as OOB swap, plus the updated drawer content
        res.render('partials/cart-drawer-content', {
          layout: false,
          cart,
          count
        });
      } else {
        res.json({ success: true, count });
      }
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }

  async updateQuantity(req, res) {
    try {
      const { product_id, quantity } = req.body;
      req.session.cart = req.session.cart || { items: [] };

      const targetProductId = req.params.productId || product_id;
      await cartService.updateQuantity(req.session.userId, req.session.cart, targetProductId, parseInt(quantity));
      
      const cart = await cartService.getCart(req.session.userId, req.session.cart);
      const count = await cartService.getCartCount(req.session.userId, req.session.cart);

      if (req.headers['hx-request']) {
        res.render('partials/cart-drawer-content', {
          layout: false,
          cart,
          count
        });
      } else {
        res.json({ success: true, count });
      }
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }

  async removeItem(req, res) {
    try {
      const { product_id } = req.body;
      req.session.cart = req.session.cart || { items: [] };

      const targetProductId = req.params.productId || product_id;
      await cartService.removeFromCart(req.session.userId, req.session.cart, targetProductId);
      
      const cart = await cartService.getCart(req.session.userId, req.session.cart);
      const count = await cartService.getCartCount(req.session.userId, req.session.cart);

      if (req.headers['hx-request']) {
        res.render('partials/cart-drawer-content', {
          layout: false,
          cart,
          count
        });
      } else {
        res.json({ success: true, count });
      }
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new CartController();
