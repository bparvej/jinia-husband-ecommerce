const express = require('express');
const router = express.Router();
const userController = require('./user.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');
const { csrfProtection } = require('../../middleware/csrf');

router.get('/admin/users', requireAuth, requireAdmin, checkPermission('read', 'User'), userController.adminIndex);
router.post('/api/v1/users', requireAuth, requireAdmin, checkPermission('create', 'User'), csrfProtection, auditLog('create', 'User'), userController.store);
router.put('/api/v1/users/:id', requireAuth, requireAdmin, checkPermission('update', 'User'), csrfProtection, auditLog('update', 'User'), userController.update);
router.delete('/api/v1/users/:id', requireAuth, requireAdmin, checkPermission('delete', 'User'), csrfProtection, auditLog('delete', 'User'), userController.delete);

module.exports = router;
