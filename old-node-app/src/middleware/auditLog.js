const db = require('../models');
const logger = require('../utils/logger');

/**
 * Audit log middleware — logs admin actions
 */
function auditLog(action, subject) {
  return async (req, res, next) => {
    // Store original json/send methods to capture response
    const originalJson = res.json.bind(res);
    const originalSend = res.send.bind(res);

    const logEntry = async () => {
      try {
        await db.AuditLog.create({
          user_id: req.session?.userId || null,
          action,
          subject,
          subject_id: req.params.id || null,
          details: {
            method: req.method,
            url: req.originalUrl,
            body: sanitizeBody(req.body),
            statusCode: res.statusCode,
          },
          ip_address: req.ip,
          user_agent: req.get('User-Agent')?.substring(0, 500),
        });
      } catch (err) {
        logger.error('Failed to create audit log', { error: err.message });
      }
    };

    res.json = (data) => {
      logEntry();
      return originalJson(data);
    };

    res.send = (data) => {
      logEntry();
      return originalSend(data);
    };

    next();
  };
}

function sanitizeBody(body) {
  if (!body) return {};
  const sanitized = { ...body };
  delete sanitized.password;
  delete sanitized._csrf;
  return sanitized;
}

module.exports = { auditLog };
