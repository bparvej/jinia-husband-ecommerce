const db = require('../../models');

class CartRepository {
  async findOrCreateCart(userId) {
    const [cart] = await db.Cart.findOrCreate({
      where: { user_id: userId },
      defaults: { user_id: userId },
    });
    return cart;
  }

  async getCartWithItems(userId) {
    return db.Cart.findOne({
      where: { user_id: userId },
      include: [{
        model: db.CartItem,
        include: [{
          model: db.Product,
          attributes: ['id', 'name', 'price', 'compare_price', 'image', 'slug', 'is_active'],
          include: [{ model: db.Inventory, attributes: ['quantity'] }],
        }],
      }],
    });
  }

  async addItem(cartId, productId, quantity = 1) {
    const existing = await db.CartItem.findOne({
      where: { cart_id: cartId, product_id: productId },
    });

    if (existing) {
      existing.quantity += quantity;
      await existing.save();
      return existing;
    }

    return db.CartItem.create({ cart_id: cartId, product_id: productId, quantity });
  }

  async updateItemQuantity(itemId, quantity) {
    const item = await db.CartItem.findByPk(itemId);
    if (!item) throw new Error('Cart item not found');
    item.quantity = quantity;
    await item.save();
    return item;
  }

  async removeItem(itemId) {
    const item = await db.CartItem.findByPk(itemId);
    if (!item) throw new Error('Cart item not found');
    await item.destroy();
  }

  async clearCart(cartId) {
    await db.CartItem.destroy({ where: { cart_id: cartId } });
  }
}

module.exports = new CartRepository();
