const db = require('../../models');

class NotificationRepository {
  async create(data) {
    return db.Notification.create(data);
  }

  async findAll({ where = {}, offset = 0, limit = 20 } = {}) {
    return db.Notification.findAndCountAll({
      where,
      order: [['created_at', 'DESC']],
      offset,
      limit,
    });
  }

  async markAsRead(id, userId) {
    return db.Notification.update({ is_read: true }, { where: { id, user_id: userId } });
  }

  async markAllAsRead(userId) {
    return db.Notification.update({ is_read: true }, { where: { user_id: userId, is_read: false } });
  }

  async delete(id, userId) {
    return db.Notification.destroy({ where: { id, user_id: userId } });
  }

  async count(where = {}) {
    return db.Notification.count({ where });
  }
}

module.exports = new NotificationRepository();
