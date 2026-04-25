const paymentRepository = require('./payment.repository');
const logger = require('../../utils/logger');

class PaymentService {
  async updatePaymentStatus(orderId, status, transactionId = null) {
    await paymentRepository.updateStatus(orderId, status, transactionId);
    logger.info('Payment status updated', { orderId, status });
  }

  async getPaymentByOrderId(orderId) {
    return paymentRepository.findByOrderId(orderId);
  }
}

module.exports = new PaymentService();
