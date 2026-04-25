const express = require('express');
const router = express.Router();
const productController = require('./product.controller');
const { requireAuth, requireAdmin } = require('../../middleware/auth');
const { checkPermission } = require('../../middleware/rbac');
const { auditLog } = require('../../middleware/auditLog');
const upload = require('../../middleware/uploadHandler');

// Admin routes
router.get('/admin/products', requireAuth, requireAdmin, checkPermission('read', 'Product'), productController.adminIndex);
router.get('/admin/products/create', requireAuth, requireAdmin, checkPermission('create', 'Product'), productController.adminCreate);
router.post('/admin/products', requireAuth, requireAdmin, checkPermission('create', 'Product'), upload.single('image'), auditLog('create', 'Product'), productController.adminStore);
router.get('/admin/products/:id/edit', requireAuth, requireAdmin, checkPermission('update', 'Product'), productController.adminEdit);
router.put('/admin/products/:id', requireAuth, requireAdmin, checkPermission('update', 'Product'), upload.single('image'), auditLog('update', 'Product'), productController.adminUpdate);
router.delete('/admin/products/:id', requireAuth, requireAdmin, checkPermission('delete', 'Product'), auditLog('delete', 'Product'), productController.adminDelete);

// API routes
router.get('/api/v1/products', productController.apiList);

module.exports = router;
