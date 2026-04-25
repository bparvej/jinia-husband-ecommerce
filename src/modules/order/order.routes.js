const express = require('express');
const router = express.Router();
const orderController = require('./order.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

// Dashboard
router.get('/admin/dashboard', requireAuth, requireAdmin, orderController.dashboard);

// Orders
router.get('/admin/orders', requireAuth, requireAdmin, checkPermission('read', 'Order'), orderController.adminIndex);
router.get('/admin/orders/:id', requireAuth, requireAdmin, checkPermission('read', 'Order'), orderController.adminDetail);
router.put('/api/v1/orders/:id/status', requireAuth, requireAdmin, checkPermission('update', 'Order'), auditLog('update', 'Order'), orderController.updateStatus);

module.exports = router;
