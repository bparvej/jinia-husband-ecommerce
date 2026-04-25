const orderRepository = require('./order.repository');
const inventoryRepository = require('../inventory/inventory.repository');
const db = require('../../models');
const { paginate } = require('../../utils/pagination');
const { generateOrderNumber } = require('../../utils/validators');
const logger = require('../../utils/logger');

class OrderService {
  async getOrders(page = 1, limit = 12, filters = {}) {
    const where = {};
    if (filters.status) where.status = filters.status;
    if (filters.search) {
      const { Op } = require('sequelize');
      where[Op.or] = [
        { order_number: { [Op.iLike]: `%${filters.search}%` } },
      ];
    }

    const total = await orderRepository.count({ where });
    const pagination = paginate(page, limit, total);
    const result = await orderRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { orders: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async getOrderById(id) {
    const order = await orderRepository.findById(id);
    if (!order) throw new Error('Order not found');
    return order;
  }

  async createOrder(userId, cartItems, shippingData) {
    const transaction = await db.sequelize.transaction();
    const productRepository = require('../product/product.repository');
    const paymentRepository = require('../payment/payment.repository');

    try {
      // Calculate totals
      let subtotal = 0;
      const orderItems = [];

      for (const item of cartItems) {
        const product = await productRepository.findByPk(item.product_id, { transaction });
        if (!product) throw new Error(`Product not found: ${item.product_id}`);

        // Deduct inventory
        await inventoryRepository.decrementStock(item.product_id, item.quantity, transaction);

        const itemTotal = parseFloat(product.price) * item.quantity;
        subtotal += itemTotal;

        orderItems.push({
          product_id: product.id,
          product_name: product.name,
          quantity: item.quantity,
          unit_price: product.price,
          total_price: itemTotal,
        });

        // Update sold count
        await productRepository.incrementSoldCount(product.id, item.quantity, transaction);
      }

      const shippingCost = subtotal >= 5000 ? 0 : 200;
      const total = subtotal + shippingCost;

      // Create order
      const order = await orderRepository.create({
        order_number: generateOrderNumber(),
        user_id: userId,
        subtotal,
        shipping_cost: shippingCost,
        total,
        ...shippingData,
      }, transaction);

      // Create order items
      for (const item of orderItems) {
        await orderRepository.createOrderItem({ ...item, order_id: order.id }, transaction);
      }

      // Create payment record
      await paymentRepository.create({
        order_id: order.id,
        method: shippingData.payment_method || 'cod',
        amount: total,
        status: 'pending',
      }, transaction);

      await transaction.commit();
      logger.info('Order created', { orderId: order.id, orderNumber: order.order_number, total });
      return order;
    } catch (err) {
      await transaction.rollback();
      logger.error('Order creation failed', { error: err.message, userId });
      throw err;
    }
  }

  async updateOrderStatus(id, status) {
    const order = await orderRepository.updateStatus(id, status);
    logger.info('Order status updated', { orderId: id, status });
    return order;
  }

  async getDashboardStats() {
    const userRepository = require('../user/user.repository');
    const productRepository = require('../product/product.repository');

    const stats = await orderRepository.getOrderStats();
    const recentOrders = await orderRepository.getRecentOrders(5);
    const lowStock = await inventoryRepository.getLowStock();
    const totalProducts = await productRepository.count({ is_active: true });
    const totalCustomers = await userRepository.count({ role_id: 4 }); // Assuming 4 is customer role

    return { stats, recentOrders, lowStock, totalProducts, totalCustomers };
  }
}

module.exports = new OrderService();
