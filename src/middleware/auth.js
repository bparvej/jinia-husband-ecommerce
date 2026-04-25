const { defineAbilitiesFor } = require('../config/casl');

/**
 * Require authenticated user
 */
function requireAuth(req, res, next) {
  if (!req.session || !req.session.userId) {
    if (req.headers['hx-request']) {
      return res.status(401).send('<div class="alert alert-error">Please login to continue</div>');
    }
    return res.redirect('/login');
  }
  next();
}

/**
 * Require admin role (super_admin, admin, or manager)
 */
function requireAdmin(req, res, next) {
  if (!req.session || !req.session.userId) {
    return res.redirect('/login');
  }
  const adminRoles = ['super_admin', 'admin', 'manager'];
  if (!req.user || !req.user.Role || !adminRoles.includes(req.user.Role.name)) {
    if (req.headers['hx-request']) {
      return res.status(403).send('<div class="alert alert-error">Access denied</div>');
    }
    return res.status(403).render('pages/error', {
      layout: 'layouts/main',
      title: 'Access Denied',
      message: 'You do not have permission to access this page.',
      error: { status: 403 },
    });
  }
  next();
}

/**
 * Load user from session and attach CASL abilities
 */
function loadUser(db) {
  return async (req, res, next) => {
    if (req.session && req.session.userId) {
      try {
        const user = await db.User.findByPk(req.session.userId, {
          include: [{ model: db.Role }],
        });
        if (user && user.is_active) {
          req.user = user;
          req.ability = defineAbilitiesFor(user);
          res.locals.currentUser = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.Role.name,
            avatar: user.avatar,
          };
          res.locals.ability = req.ability;
        } else {
          req.session.destroy();
        }
      } catch (err) {
        // Session invalid, continue as guest
      }
    }

    if (!req.user) {
      req.ability = defineAbilitiesFor(null);
      res.locals.currentUser = null;
      res.locals.ability = req.ability;
    }

    next();
  };
}

module.exports = { requireAuth, requireAdmin, loadUser };
