require('dotenv').config();

module.exports = {
  development: {
    // Prioritize DATABASE_URL if present, even in dev (e.g., local Docker with external DB)
    use_env_variable: process.env.DATABASE_URL ? 'DATABASE_URL' : undefined,
    username: process.env.DB_USER || 'homei_user',
    password: process.env.DB_PASSWORD || 'homei_secret_2026',
    database: process.env.DB_NAME || 'homei_db',
    host: process.env.DB_HOST || 'db',
    port: process.env.DB_PORT || 5432,
    dialect: 'postgres',
    logging: false,
    // Apply SSL options only if DATABASE_URL is used (typical for cloud DBs)
    dialectOptions: process.env.DATABASE_URL ? {
      ssl: {
        require: true,
        rejectUnauthorized: false
      }
    } : {},
    define: {
      timestamps: true,
      underscored: true,
      paranoid: true,
    }
  },
  // Production and test environments can follow the same pattern
  production: {
    use_env_variable: process.env.DATABASE_URL ? 'DATABASE_URL' : undefined,
    username: process.env.DB_USER || null,
    password: process.env.DB_PASSWORD || null,
    database: process.env.DB_NAME || null,
    host: process.env.DB_HOST || null,
    port: process.env.DB_PORT || 5432,
    dialect: 'postgres',
    logging: false,
    dialectOptions: (process.env.DATABASE_URL || process.env.DB_SSL === 'true') ? {
      ssl: {
        require: true,
        rejectUnauthorized: false
      }
    } : {},
    define: {
      timestamps: true,
      underscored: true,
      paranoid: true,
    }
  }
};