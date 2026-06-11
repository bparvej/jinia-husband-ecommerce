'use strict';

const { Sequelize } = require('sequelize');
const env = process.env.NODE_ENV || 'development';
const config = require('../config/database')[env];

const sequelize = new Sequelize(
  config.database,
  config.username,
  config.password,
  {
    host: config.host,
    port: config.port,
    dialect: config.dialect,
    logging: config.logging,
    pool: config.pool,
    define: config.define,
    ...(config.dialectOptions ? { dialectOptions: config.dialectOptions } : {}),
  }
);

const db = {};

// Import models
db.User = require('./User')(sequelize, Sequelize.DataTypes);
db.Role = require('./Role')(sequelize, Sequelize.DataTypes);
db.Permission = require('./Permission')(sequelize, Sequelize.DataTypes);
db.RolePermission = require('./RolePermission')(sequelize, Sequelize.DataTypes);
db.Category = require('./Category')(sequelize, Sequelize.DataTypes);
db.Product = require('./Product')(sequelize, Sequelize.DataTypes);
db.ProductVariant = require('./ProductVariant')(sequelize, Sequelize.DataTypes);
db.Inventory = require('./Inventory')(sequelize, Sequelize.DataTypes);
db.Order = require('./Order')(sequelize, Sequelize.DataTypes);
db.OrderItem = require('./OrderItem')(sequelize, Sequelize.DataTypes);
db.Payment = require('./Payment')(sequelize, Sequelize.DataTypes);
db.Cart = require('./Cart')(sequelize, Sequelize.DataTypes);
db.CartItem = require('./CartItem')(sequelize, Sequelize.DataTypes);
db.Coupon = require('./Coupon')(sequelize, Sequelize.DataTypes);
db.Review = require('./Review')(sequelize, Sequelize.DataTypes);
db.AuditLog = require('./AuditLog')(sequelize, Sequelize.DataTypes);
db.Notification = require('./Notification')(sequelize, Sequelize.DataTypes);

// Set up associations
Object.keys(db).forEach((modelName) => {
  if (db[modelName].associate) {
    db[modelName].associate(db);
  }
});

db.sequelize = sequelize;
db.Sequelize = Sequelize;

module.exports = db;
