const cartService = require('./cart.service');

class CartController {
  async getCart(req, res) {
    try {
      const cart = await cartService.getCart(req.session.userId);
      res.json({ cart });
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  }

  async addToCart(req, res) {
    try {
      const { product_id, quantity } = req.body;
      await cartService.addToCart(req.session.userId, product_id, parseInt(quantity) || 1);
      const count = await cartService.getCartCount(req.session.userId);

      if (req.headers['hx-request']) {
        return res.send(`<span id="cart-count" hx-swap-oob="true">${count}</span>`);
      }
      res.json({ success: true, count });
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }

  async updateQuantity(req, res) {
    try {
      const { quantity } = req.body;
      await cartService.updateQuantity(req.params.itemId, parseInt(quantity));
      const count = await cartService.getCartCount(req.session.userId);

      if (req.headers['hx-request']) {
        return res.send(`<span id="cart-count" hx-swap-oob="true">${count}</span>`);
      }
      res.json({ success: true, count });
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }

  async removeItem(req, res) {
    try {
      await cartService.removeFromCart(req.params.itemId);
      const count = await cartService.getCartCount(req.session.userId);

      if (req.headers['hx-request']) {
        return res.send(`<span id="cart-count" hx-swap-oob="true">${count}</span>`);
      }
      res.json({ success: true, count });
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new CartController();
