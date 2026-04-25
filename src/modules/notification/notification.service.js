const notificationRepository = require('./notification.repository');
const { paginate } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class NotificationService {
  async notify(userId, title, message, type = 'info', link = null) {
    const notification = await notificationRepository.create({
      user_id: userId,
      title,
      message,
      type,
      link,
    });
    logger.info('Notification sent', { userId, type, title });
    return notification;
  }

  async getUserNotifications(userId, page = 1, limit = 20) {
    const where = { user_id: userId };
    const total = await notificationRepository.count(where);
    const pagination = paginate(page, limit, total);
    
    const result = await notificationRepository.findAll({
      where,
      offset: pagination.offset,
      limit: pagination.limit,
    });

    return { 
      notifications: result.rows, 
      pagination: { ...pagination, totalItems: result.count } 
    };
  }

  async markRead(id, userId) {
    return notificationRepository.markAsRead(id, userId);
  }

  async markAllRead(userId) {
    return notificationRepository.markAllAsRead(userId);
  }

  async clearNotification(id, userId) {
    return notificationRepository.delete(id, userId);
  }

  // System alerts
  async notifyLowStock(productId, productName, quantity) {
    return this.notify(null, 'Low Stock Alert', `Product "${productName}" is low on stock (${quantity} left).`, 'stock', `/admin/inventory`);
  }

  async notifyNewOrder(orderId, orderNumber, total) {
    // Notify all admins or specific role
    // For simplicity, we'll notify system (user_id null)
    return this.notify(null, 'New Order Received', `Order #${orderNumber} has been placed for BDT ${total}.`, 'order', `/admin/orders/${orderId}`);
  }
}

module.exports = new NotificationService();
