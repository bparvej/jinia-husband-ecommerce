const express = require('express');
const router = express.Router();
const authController = require('./auth.controller');
const { loginLimiter } = require('../../middleware/rateLimiter');

// Pages
router.get('/login', authController.showLogin);
router.post('/login', loginLimiter, authController.login);
router.get('/logout', authController.logout);

module.exports = router;
