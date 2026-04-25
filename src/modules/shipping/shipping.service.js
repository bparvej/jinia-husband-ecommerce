const shippingRepository = require('./shipping.repository');
const { paginate } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class ShippingService {
  async getShipments(page = 1, limit = 12, filters = {}) {
    const where = {};
    if (filters.status) where.status = filters.status;
    if (filters.city) where.shipping_city = filters.city;

    const total = await shippingRepository.count(where);
    const pagination = paginate(page, limit, total);
    const result = await shippingRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { shipments: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async updateShippingStatus(orderId, status, trackingNumber = null) {
    const shipment = await shippingRepository.updateStatus(orderId, status, trackingNumber);
    logger.info('Shipping status updated', { orderId, status });
    return shipment;
  }
}

module.exports = new ShippingService();
