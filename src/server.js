const app = require('./app');
const db = require('./models');
const logger = require('./utils/logger');
const { handleDbConnectionError } = require('./utils/dbErrorHandler');

const PORT = process.env.PORT || 3000;

async function start() {
  try {
    // Test database connection
    await db.sequelize.authenticate();
    logger.info('✅ Database connection established');

    // Sync models (only in development, use migrations in production)
    if (process.env.NODE_ENV === 'development') {
      // We use migrations instead of sync
      logger.info('📦 Using Sequelize migrations for database schema');
    }

    // Start server
    app.listen(PORT, () => {
      logger.info(`🚀 HomeI Ecommerce server running on http://localhost:${PORT}`);
      logger.info(`📋 Admin panel: http://localhost:${PORT}/admin/dashboard`);
      logger.info(`🔑 Login: admin@homei.com / Admin@123`);
    });
  } catch (error) {
    handleDbConnectionError(error, logger);
    process.exit(1);
  }
}

// Handle unhandled rejections
process.on('unhandledRejection', (err) => {
  logger.error('Unhandled rejection', { error: err.message, stack: err.stack });
});

process.on('uncaughtException', (err) => {
  logger.error('Uncaught exception', { error: err.message, stack: err.stack });
  process.exit(1);
});

start();
