const db = require('../../models');

class InventoryRepository {
  async findAll({ offset = 0, limit = 20, where = {} } = {}) {
    return db.Inventory.findAndCountAll({
      include: [{
        model: db.Product,
        where,
        attributes: ['id', 'name', 'sku', 'image', 'price', 'is_active'],
        include: [{ model: db.Category, attributes: ['id', 'name'] }],
      }],
      order: [['quantity', 'ASC']],
      offset,
      limit,
      distinct: true,
    });
  }

  async findByProductId(productId) {
    return db.Inventory.findOne({ where: { product_id: productId } });
  }

  async updateStock(productId, quantity, transaction = null) {
    const opts = transaction ? { transaction } : {};
    return db.Inventory.update(
      { quantity },
      { where: { product_id: productId }, ...opts }
    );
  }

  async decrementStock(productId, amount, transaction) {
    const inventory = await db.Inventory.findOne({
      where: { product_id: productId },
      transaction,
      lock: true,
    });
    if (!inventory) throw new Error('Inventory record not found');
    if (inventory.quantity < amount) throw new Error('Insufficient stock');
    inventory.quantity -= amount;
    await inventory.save({ transaction });
    return inventory;
  }

  async getLowStock(threshold) {
    return db.Inventory.findAll({
      where: db.Sequelize.where(
        db.Sequelize.col('quantity'),
        '<=',
        db.Sequelize.col('low_stock_threshold')
      ),
      include: [{
        model: db.Product,
        attributes: ['id', 'name', 'sku', 'image'],
        where: { is_active: true },
      }],
      order: [['quantity', 'ASC']],
    });
  }

  async count(where = {}) {
    return db.Inventory.count({ where });
  }

  async create(data, transaction = null) {
    const opts = transaction ? { transaction } : {};
    return db.Inventory.create(data, opts);
  }
}

module.exports = new InventoryRepository();
