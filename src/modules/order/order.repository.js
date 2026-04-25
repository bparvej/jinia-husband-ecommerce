const db = require('../../models');

class OrderRepository {
  async findAll({ offset = 0, limit = 12, where = {}, order = [['created_at', 'DESC']] } = {}) {
    return db.Order.findAndCountAll({
      where,
      include: [
        { model: db.User, attributes: ['id', 'name', 'email', 'phone'] },
        { model: db.Payment, attributes: ['method', 'status', 'amount'] },
      ],
      order,
      offset,
      limit,
      distinct: true,
    });
  }

  async findById(id) {
    return db.Order.findByPk(id, {
      include: [
        { model: db.User, attributes: ['id', 'name', 'email', 'phone'] },
        { model: db.OrderItem, include: [{ model: db.Product, attributes: ['id', 'name', 'image', 'slug'] }] },
        { model: db.Payment },
        { model: db.Coupon },
      ],
    });
  }

  async findByOrderNumber(orderNumber) {
    return db.Order.findOne({
      where: { order_number: orderNumber },
      include: [
        { model: db.User, attributes: ['id', 'name', 'email'] },
        { model: db.OrderItem, include: [{ model: db.Product }] },
        { model: db.Payment },
      ],
    });
  }

  async create(data, transaction) {
    return db.Order.create(data, { transaction });
  }

  async updateStatus(id, status) {
    const order = await db.Order.findByPk(id);
    if (!order) throw new Error('Order not found');
    order.status = status;
    await order.save();
    return order;
  }

  async getRecentOrders(limit = 5) {
    return db.Order.findAll({
      include: [
        { model: db.User, attributes: ['id', 'name'] },
        { model: db.Payment, attributes: ['method', 'status'] },
      ],
      order: [['created_at', 'DESC']],
      limit,
    });
  }

  async getOrderStats() {
    const [results] = await db.sequelize.query(`
      SELECT 
        COUNT(*) as total_orders,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
        COUNT(CASE WHEN status = 'processing' THEN 1 END) as processing_orders,
        COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_orders,
        COALESCE(SUM(CASE WHEN status != 'cancelled' AND status != 'refunded' THEN total ELSE 0 END), 0) as total_revenue
      FROM orders
      WHERE deleted_at IS NULL
    `);
    return results[0];
  }

  async count(where = {}) {
    return db.Order.count({ where });
  }

  async createOrderItem(data, transaction = null) {
    const opts = transaction ? { transaction } : {};
    return db.OrderItem.create(data, opts);
  }
}

module.exports = new OrderRepository();
