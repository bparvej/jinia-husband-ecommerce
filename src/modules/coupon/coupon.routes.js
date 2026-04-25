const express = require('express');
const router = express.Router();
const couponController = require('./coupon.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

router.get('/admin/coupons', requireAuth, requireAdmin, checkPermission('read', 'Coupon'), couponController.adminIndex);
router.post('/api/v1/coupons', requireAuth, requireAdmin, checkPermission('create', 'Coupon'), auditLog('create', 'Coupon'), couponController.store);
router.put('/api/v1/coupons/:id', requireAuth, requireAdmin, checkPermission('update', 'Coupon'), auditLog('update', 'Coupon'), couponController.update);
router.delete('/api/v1/coupons/:id', requireAuth, requireAdmin, checkPermission('delete', 'Coupon'), auditLog('delete', 'Coupon'), couponController.delete);

module.exports = router;
