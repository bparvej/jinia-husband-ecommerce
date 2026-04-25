const { csrfSync } = require('csrf-sync');

const {
  csrfSynchronisedProtection,
  generateToken,
} = csrfSync({
  getTokenFromRequest: (req) => {
    // Check body, query, or header for the token
    return req.body['_csrf'] || req.query['_csrf'] || req.headers['x-csrf-token'];
  },
  getTokenFromState: (req) => {
    return req.session.csrfToken;
  },
  storeTokenInState: (req, token) => {
    req.session.csrfToken = token;
  },
  size: 64,
});

/**
 * CSRF middleware — injects token into res.locals for EJS
 */
function csrfProtection(req, res, next) {
  csrfSynchronisedProtection(req, res, (err) => {
    if (err) {
      if (req.headers['hx-request']) {
        return res.status(403).send('<div class="toast toast-error">Session expired. Please refresh.</div>');
      }
      return res.status(403).render('pages/error', {
        layout: 'layouts/main',
        title: 'Session Expired',
        message: 'Your session has expired. Please refresh the page and try again.',
        error: { status: 403 },
      });
    }
    // Make token available in all templates
    res.locals.csrfToken = generateToken(req);
    next();
  });
}

/**
 * Generate CSRF token without protection check (for GET routes)
 */
function csrfTokenGenerator(req, res, next) {
  res.locals.csrfToken = generateToken(req);
  next();
}

module.exports = { csrfProtection, csrfTokenGenerator };
