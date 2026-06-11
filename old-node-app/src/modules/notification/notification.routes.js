const express = require('express');
const router = express.Router();
const notificationController = require('./notification.controller');
const { requireAuth } = require('../../middleware/auth');

router.get('/notifications', requireAuth, notificationController.index);
router.post('/api/v1/notifications/:id/read', requireAuth, notificationController.markAsRead);
router.post('/api/v1/notifications/read-all', requireAuth, notificationController.markAllAsRead);

module.exports = router;
