const express = require('express');
const router = express.Router();
const cartController = require('./cart.controller');
const { requireAuth } = require('../../middleware/auth');

router.get('/api/v1/cart', requireAuth, cartController.getCart);
router.post('/api/v1/cart/add', requireAuth, cartController.addToCart);
router.put('/api/v1/cart/:itemId', requireAuth, cartController.updateQuantity);
router.delete('/api/v1/cart/:itemId', requireAuth, cartController.removeItem);

module.exports = router;
