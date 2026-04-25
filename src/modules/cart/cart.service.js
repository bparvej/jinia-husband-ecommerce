const cartRepository = require('./cart.repository');

class CartService {
  async getCart(userId) {
    return cartRepository.getCartWithItems(userId);
  }

  async addToCart(userId, productId, quantity = 1) {
    const cart = await cartRepository.findOrCreateCart(userId);
    return cartRepository.addItem(cart.id, productId, quantity);
  }

  async updateQuantity(itemId, quantity) {
    if (quantity < 1) throw new Error('Quantity must be at least 1');
    return cartRepository.updateItemQuantity(itemId, quantity);
  }

  async removeFromCart(itemId) {
    return cartRepository.removeItem(itemId);
  }

  async clearCart(userId) {
    const cart = await cartRepository.findOrCreateCart(userId);
    return cartRepository.clearCart(cart.id);
  }

  async getCartCount(userId) {
    const cart = await cartRepository.getCartWithItems(userId);
    if (!cart || !cart.CartItems) return 0;
    return cart.CartItems.reduce((sum, item) => sum + item.quantity, 0);
  }
}

module.exports = new CartService();
