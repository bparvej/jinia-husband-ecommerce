const express = require('express');
const router = express.Router();
const reportController = require('./report.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');

router.get('/admin/reports', requireAuth, requireAdmin, checkPermission('read', 'Report'), reportController.adminIndex);
router.get('/api/v1/reports/sales', requireAuth, requireAdmin, checkPermission('read', 'Report'), reportController.getSalesReport);

module.exports = router;
