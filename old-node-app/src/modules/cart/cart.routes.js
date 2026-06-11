const express = require('express');
const router = express.Router();
const cartController = require('./cart.controller');
const { csrfProtection } = require('../../middleware/csrf');

router.get('/api/v1/cart', cartController.getCart);
router.get('/api/v1/cart/drawer', cartController.getCartDrawer);
router.post('/api/v1/cart/add', csrfProtection, cartController.addToCart);
router.post('/api/v1/cart/update/:productId', csrfProtection, cartController.updateQuantity);
router.post('/api/v1/cart/remove/:productId', csrfProtection, cartController.removeItem);

module.exports = router;
