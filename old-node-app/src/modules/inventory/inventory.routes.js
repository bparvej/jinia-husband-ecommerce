const express = require('express');
const router = express.Router();
const inventoryController = require('./inventory.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');

router.get('/admin/inventory', requireAuth, requireAdmin, checkPermission('read', 'Inventory'), inventoryController.adminIndex);
router.put('/api/v1/inventory/:id', requireAuth, requireAdmin, checkPermission('update', 'Inventory'), auditLog('update', 'Inventory'), inventoryController.updateStock);

module.exports = router;
