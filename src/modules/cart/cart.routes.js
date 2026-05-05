const express = require('express');
const router = express.Router();
const cartController = require('./cart.controller');
const { requireAuth } = require('../../middleware/auth');
const { csrfProtection } = require('../../middleware/csrf');

router.get('/api/v1/cart', requireAuth, cartController.getCart);
router.post('/api/v1/cart/add', requireAuth, csrfProtection, cartController.addToCart);
router.put('/api/v1/cart/:itemId', requireAuth, csrfProtection, cartController.updateQuantity);
router.delete('/api/v1/cart/:itemId', requireAuth, csrfProtection, cartController.removeItem);

module.exports = router;
