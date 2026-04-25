const express = require('express');
const router = express.Router();
const shippingController = require('./shipping.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

router.get('/admin/shipping', requireAuth, requireAdmin, checkPermission('read', 'Order'), shippingController.adminIndex);
router.post('/api/v1/shipping/:id/status', requireAuth, requireAdmin, checkPermission('update', 'Order'), auditLog('update', 'Order'), shippingController.updateStatus);

module.exports = router;
