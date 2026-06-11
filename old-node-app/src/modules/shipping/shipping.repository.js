const db = require('../../models');
const { Op } = require('sequelize');

class ShippingRepository {
  async findAll({ offset = 0, limit = 12, where = {} } = {}) {
    return db.Order.findAndCountAll({
      where: {
        ...where,
        status: { [Op.in]: ['processing', 'shipped', 'delivered'] }
      },
      attributes: ['id', 'order_number', 'status', 'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city', 'updated_at'],
      order: [['updated_at', 'DESC']],
      offset,
      limit,
    });
  }

  async updateStatus(orderId, status, trackingNumber = null) {
    const order = await db.Order.findByPk(orderId);
    if (!order) throw new Error('Order not found');
    
    const updateData = { status };
    if (trackingNumber) {
      // If we had a tracking_number field in Order, we'd update it here.
      // Since it's not in the migration, I'll assume status is enough for now
      // or we could add it to a notes field or similar.
      order.notes = (order.notes || '') + `\nTracking Number: ${trackingNumber}`;
    }
    
    return order.update(updateData);
  }

  async count(options = {}) {
    const where = options.where || {};
    return db.Order.count({
      ...options,
      where: {
        ...where,
        status: { [Op.in]: ['processing', 'shipped', 'delivered'] }
      }
    });
  }
}

module.exports = new ShippingRepository();
