const notificationService = require('./notification.service');
const logger = require('../../utils/logger');

class NotificationController {
  async index(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const userId = req.session.userId;
      const { notifications, pagination } = await notificationService.getUserNotifications(userId, page);

      if (req.headers['hx-request']) {
        return res.render('partials/notification-list', { notifications, pagination });
      }

      res.render('pages/notifications', {
        layout: 'layouts/main',
        title: 'Your Notifications',
        notifications,
        pagination,
      });
    } catch (err) {
      logger.error('Notification fetch error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async markAsRead(req, res) {
    try {
      await notificationService.markRead(req.params.id, req.session.userId);
      if (req.headers['hx-request']) {
        return res.send(''); // Remove from list or update UI
      }
      res.redirect('back');
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }

  async markAllAsRead(req, res) {
    try {
      await notificationService.markAllRead(req.session.userId);
      if (req.headers['hx-request']) {
        return res.send('');
      }
      res.redirect('back');
    } catch (err) {
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new NotificationController();
