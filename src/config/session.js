const session = require('express-session');
const MySQLStore = require('express-mysql-session')(session);
const mysql = require('mysql2/promise');

const options = {
  host: process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.DB_PORT, 10) || 3306,
  database: process.env.DB_NAME || 'homei_db',
  user: process.env.DB_USER || 'homei_user',
  password: process.env.DB_PASSWORD || 'homei_secret_2026',
};

const sessionStore = new MySQLStore({
  createDatabaseTable: true,
  schema: {
    tableName: 'sessions',
    columnNames: {
      session_id: 'session_id',
      expires: 'expires',
      data: 'data'
    }
  }
}, mysql.createPool(options));

const sessionConfig = {
  store: sessionStore,
  secret: process.env.SESSION_SECRET || 'homei_session_s3cret_k3y_2026',
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: process.env.NODE_ENV === 'production',
    httpOnly: true,
    maxAge: 24 * 60 * 60 * 1000, // 24 hours
    sameSite: 'lax',
  },
  name: 'homei.sid',
};

module.exports = sessionConfig;
