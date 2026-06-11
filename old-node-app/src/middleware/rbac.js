/**
 * CASL RBAC middleware
 * Checks if user can perform action on subject
 */
function checkPermission(action, subject) {
  return (req, res, next) => {
    if (!req.ability) {
      return res.status(403).json({ error: 'No permissions defined' });
    }

    if (req.ability.can(action, subject)) {
      return next();
    }

    if (req.headers['hx-request']) {
      return res.status(403).send(
        '<div class="toast toast-error">You don\'t have permission to perform this action</div>'
      );
    }

    return res.status(403).render('pages/error', {
      layout: 'layouts/main',
      title: 'Forbidden',
      message: `You don't have permission to ${action} ${subject}.`,
      error: { status: 403 },
    });
  };
}

module.exports = { checkPermission };
