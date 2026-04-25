const express = require('express');
const router = express.Router();
const paymentController = require('./payment.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

router.get('/admin/payments', requireAuth, requireAdmin, checkPermission('read', 'Payment'), paymentController.adminIndex);
router.post('/api/v1/payments/:orderId/status', requireAuth, requireAdmin, checkPermission('update', 'Payment'), auditLog('update', 'Payment'), paymentController.updateStatus);

module.exports = router;
