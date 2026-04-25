const express = require('express');
const router = express.Router();
const reviewController = require('./review.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

router.get('/admin/reviews', requireAuth, requireAdmin, checkPermission('read', 'Review'), reviewController.adminIndex);
router.post('/api/v1/reviews/:id/approve', requireAuth, requireAdmin, checkPermission('update', 'Review'), auditLog('update', 'Review'), reviewController.approve);
router.delete('/api/v1/reviews/:id', requireAuth, requireAdmin, checkPermission('delete', 'Review'), auditLog('delete', 'Review'), reviewController.delete);

module.exports = router;
