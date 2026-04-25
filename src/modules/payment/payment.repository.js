const db = require('../../models');

class PaymentRepository {
  async create(data, transaction = null) {
    const opts = transaction ? { transaction } : {};
    return db.Payment.create(data, opts);
  }

  async findByOrderId(orderId) {
    return db.Payment.findOne({ where: { order_id: orderId } });
  }

  async updateStatus(orderId, status, transactionId = null) {
    const data = { status };
    if (transactionId) data.transaction_id = transactionId;
    return db.Payment.update(data, { where: { order_id: orderId } });
  }

  async count(options = {}) {
    return db.Payment.count(options);
  }

  async findAll(options = {}) {
    return db.Payment.findAndCountAll({
      ...options,
      order: [['created_at', 'DESC']]
    });
  }
}

module.exports = new PaymentRepository();
