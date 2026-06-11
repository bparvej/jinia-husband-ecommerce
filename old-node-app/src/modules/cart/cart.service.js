const cartRepository = require('./cart.repository');
const db = require('../../models');

class CartService {
  async getCart(userId, sessionCart) {
    if (userId) {
      return cartRepository.getCartWithItems(userId);
    }
    return this.getGuestCart(sessionCart);
  }

  async getGuestCart(sessionCart) {
    const items = sessionCart?.items || [];
    if (items.length === 0) {
      return { CartItems: [] };
    }

    const productIds = items.map(i => i.product_id);
    const products = await db.Product.findAll({
      where: { id: productIds, is_active: true },
      include: [{ model: db.Inventory, attributes: ['quantity'] }],
    });

    const productMap = {};
    products.forEach(p => {
      productMap[p.id] = p;
    });

    const cartItems = items.map(item => {
      const product = productMap[item.product_id];
      if (!product) return null;
      return {
        id: `guest-${item.product_id}`,
        product_id: item.product_id,
        quantity: item.quantity,
        Product: product,
      };
    }).filter(Boolean);

    return { CartItems: cartItems };
  }

  async addToCart(userId, sessionCart, productId, quantity = 1) {
    if (userId) {
      const cart = await cartRepository.findOrCreateCart(userId);
      return cartRepository.addItem(cart.id, productId, quantity);
    }

    // Guest cart session update
    sessionCart.items = sessionCart.items || [];
    const existing = sessionCart.items.find(i => Number(i.product_id) === Number(productId));
    if (existing) {
      existing.quantity += quantity;
    } else {
      sessionCart.items.push({ product_id: Number(productId), quantity });
    }
    return sessionCart;
  }

  async updateQuantity(userId, sessionCart, productId, quantity) {
    if (quantity < 1) throw new Error('Quantity must be at least 1');

    if (userId) {
      const cart = await cartRepository.findOrCreateCart(userId);
      const item = await db.CartItem.findOne({
        where: { cart_id: cart.id, product_id: productId }
      });
      if (!item) throw new Error('Cart item not found');
      return cartRepository.updateItemQuantity(item.id, quantity);
    }

    // Guest cart session update
    sessionCart.items = sessionCart.items || [];
    const item = sessionCart.items.find(i => Number(i.product_id) === Number(productId));
    if (!item) throw new Error('Cart item not found');
    item.quantity = quantity;
    return sessionCart;
  }

  async removeFromCart(userId, sessionCart, productId) {
    if (userId) {
      const cart = await cartRepository.findOrCreateCart(userId);
      const item = await db.CartItem.findOne({
        where: { cart_id: cart.id, product_id: productId }
      });
      if (!item) throw new Error('Cart item not found');
      return cartRepository.removeItem(item.id);
    }

    // Guest cart session update
    if (sessionCart.items) {
      sessionCart.items = sessionCart.items.filter(i => Number(i.product_id) !== Number(productId));
    }
    return sessionCart;
  }

  async clearCart(userId, sessionCart) {
    if (userId) {
      const cart = await cartRepository.findOrCreateCart(userId);
      return cartRepository.clearCart(cart.id);
    }

    // Guest cart session update
    if (sessionCart) {
      sessionCart.items = [];
    }
  }

  async getCartCount(userId, sessionCart) {
    if (userId) {
      const cart = await cartRepository.getCartWithItems(userId);
      if (!cart || !cart.CartItems) return 0;
      return cart.CartItems.reduce((sum, item) => sum + item.quantity, 0);
    }

    if (!sessionCart || !sessionCart.items) return 0;
    return sessionCart.items.reduce((sum, item) => sum + item.quantity, 0);
  }
}

module.exports = new CartService();
