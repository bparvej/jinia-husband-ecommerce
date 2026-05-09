require('dotenv').config();

module.exports = {
  development: {
    username: process.env.DB_USER || 'homei_user',
    password: process.env.DB_PASSWORD || 'homei_secret_2026',
    database: process.env.DB_NAME || 'homei_db',
    host: process.env.DB_HOST || 'db',
    port: process.env.DB_PORT || 5432,
    dialect: 'postgres',
    logging: false,
    define: {
      timestamps: true,
      underscored: true,
      paranoid: true,
    }
  },
  // Production and test environments can follow the same pattern
  production: { 
    use_env_variable: 'DATABASE_URL', 
    dialect: 'postgres', 
    logging: false,
    define: {
      timestamps: true,
      underscored: true,
      paranoid: true,
    }
  }
};