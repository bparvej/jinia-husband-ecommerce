const authService = require('./auth.service');
const logger = require('../../utils/logger');

class AuthController {
  async showLogin(req, res) {
    if (req.session && req.session.userId) {
      return res.redirect('/admin/dashboard');
    }
    res.render('pages/login', {
      layout: 'layouts/main',
      title: 'Login — HomeI Admin',
      error: null,
    });
  }

  async login(req, res) {
    try {
      const { email, password } = req.body;

      if (!email || !password) {
        return res.render('pages/login', {
          layout: 'layouts/main',
          title: 'Login — HomeI Admin',
          error: 'Please enter email and password',
        });
      }

      const user = await authService.login(email, password);

      req.session.userId = user.id;
      req.session.userRole = user.Role.name;

      // Redirect based on role
      const adminRoles = ['super_admin', 'admin', 'manager'];
      if (adminRoles.includes(user.Role.name)) {
        return res.redirect('/admin/dashboard');
      }

      return res.redirect('/');
    } catch (err) {
      logger.warn('Login failed', { email: req.body.email, error: err.message });
      res.render('pages/login', {
        layout: 'layouts/main',
        title: 'Login — HomeI Admin',
        error: err.message,
      });
    }
  }

  async logout(req, res) {
    const userId = req.session?.userId;
    req.session.destroy((err) => {
      if (err) {
        logger.error('Session destroy error', { error: err.message });
      }
      logger.info(`User logged out`, { userId });
      res.clearCookie('homei.sid');
      res.redirect('/login');
    });
  }
}

module.exports = new AuthController();
