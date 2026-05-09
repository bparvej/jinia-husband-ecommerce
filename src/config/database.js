require('dotenv').config();

module.exports = {
  development: {
    use_env_variable: process.env.DATABASE_URL ? 'DATABASE_URL' : undefined,
    username: process.env.DB_USER || 'homei_user',
    password: process.env.DB_PASSWORD || 'homei_secret_2026',
    database: process.env.DB_NAME || 'homei_db',
    host: process.env.DB_HOST || 'db',
    port: parseInt(process.env.DB_PORT, 10) || 5432,
    dialect: 'postgres',
    logging: false,
    pool: {
      max: 10,
      min: 2,
      acquire: 30000,
      idle: 10000,
    },
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
    },
  },
  production: {
    use_env_variable: process.env.DATABASE_URL ? 'DATABASE_URL' : undefined,
    username: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    host: process.env.DB_HOST,
    port: parseInt(process.env.DB_PORT, 10) || 5432,
    dialect: 'postgres',
    logging: false,
    pool: {
      max: 20,
      min: 5,
      acquire: 30000,
      idle: 10000,
    },
    define: {
      timestamps: true,
      underscored: true,
      paranoid: true,
    },
    dialectOptions: {
      ssl: {
        require: true,
        rejectUnauthorized: false,
      },
    },
  },
};
