const app = require('./app');
const db = require('./models');
const logger = require('./utils/logger');

const PORT = process.env.PORT || 10000;

async function start() {
  try {
    if (process.env.NODE_ENV === 'production' && !process.env.DATABASE_URL && !process.env.DB_HOST) {
      logger.error('❌ FATAL: Database connection info is missing. Link your DB in Render or set DB_HOST.');
      process.exit(1);
    }

    // Test database connection
    await db.sequelize.authenticate();
    logger.info('✅ Database connection established');

    // Sync models (only in development, use migrations in production)
    if (process.env.NODE_ENV === 'development') {
      // We use migrations instead of sync
      logger.info('📦 Using Sequelize migrations for database schema');
    }

    // Start server
    const server = app.listen(PORT, '0.0.0.0', () => {
      logger.info(`🚀 Server listening on 0.0.0.0:${PORT} (Render Policy Compliant)`);
    });

    // Graceful shutdown for Render restarts
    process.on('SIGTERM', () => {
      logger.info('SIGTERM received. Shutting down gracefully...');
      server.close(() => process.exit(0));
    });

  } catch (error) {
    logger.error('❌ Failed to start server', { error: error.message, stack: error.stack });
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
