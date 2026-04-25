const logger = require('../utils/logger');

/**
 * Global error handler middleware
 */
function errorHandler(err, req, res, _next) {
  // Log the error
  logger.error('Unhandled error', {
    message: err.message,
    stack: err.stack,
    url: req.originalUrl,
    method: req.method,
    userId: req.session?.userId,
    ip: req.ip,
  });

  const statusCode = err.statusCode || err.status || 500;
  const message = process.env.NODE_ENV === 'production' && statusCode === 500
    ? 'Something went wrong. Please try again later.'
    : err.message;

  // HTMX request — return partial
  if (req.headers['hx-request']) {
    return res.status(statusCode).send(
      `<div class="toast toast-error">${message}</div>`
    );
  }

  // API request
  if (req.originalUrl.startsWith('/api/')) {
    return res.status(statusCode).json({
      error: message,
      ...(process.env.NODE_ENV !== 'production' && { stack: err.stack }),
    });
  }

  // Page request
  res.status(statusCode).render('pages/error', {
    layout: 'layouts/main',
    title: `Error ${statusCode}`,
    message,
    error: { status: statusCode },
  });
}

/**
 * 404 handler
 */
function notFoundHandler(req, res) {
  if (req.headers['hx-request']) {
    return res.status(404).send('<div class="toast toast-error">Page not found</div>');
  }

  if (req.originalUrl.startsWith('/api/')) {
    return res.status(404).json({ error: 'Resource not found' });
  }

  res.status(404).render('pages/error', {
    layout: 'layouts/main',
    title: 'Page Not Found',
    message: 'The page you are looking for does not exist.',
    error: { status: 404 },
  });
}

module.exports = { errorHandler, notFoundHandler };
