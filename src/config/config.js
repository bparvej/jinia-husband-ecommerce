require('dotenv').config();

const commonConfig = {
  dialect: 'postgres',
  logging: false,

  define: {
    timestamps: true,
    underscored: true,
    paranoid: true,
  }
};

const sslConfig =
  process.env.DATABASE_URL || process.env.DB_SSL === 'true'
    ? {
      ssl: {
        require: true,
        rejectUnauthorized: false,
      },
    }
    : {};

module.exports = {
  development: {
    ...commonConfig,

    use_env_variable: process.env.DATABASE_URL
      ? 'DATABASE_URL'
      : undefined,

    username: process.env.DB_USER || 'homei_user',
    password: process.env.DB_PASSWORD || 'homei_secret_2026',
    database: process.env.DB_NAME || 'homei_db',
    host: process.env.DB_HOST || 'db',
    port: process.env.DB_PORT || 5432,

    dialectOptions: sslConfig,
  },

  production: {
    ...commonConfig,

    use_env_variable: 'DATABASE_URL',

    dialectOptions: sslConfig,
  },

  test: {
    ...commonConfig,

    use_env_variable: process.env.DATABASE_URL
      ? 'DATABASE_URL'
      : undefined,

    username: process.env.DB_USER || 'postgres',
    password: process.env.DB_PASSWORD || 'postgres',
    database: process.env.DB_NAME || 'homei_test',
    host: process.env.DB_HOST || '127.0.0.1',
    port: process.env.DB_PORT || 5432,

    dialectOptions: sslConfig,
  }
};