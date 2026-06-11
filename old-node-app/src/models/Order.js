'use strict';

module.exports = (sequelize, DataTypes) => {
  const Order = sequelize.define('Order', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    order_number: {
      type: DataTypes.STRING(50),
      allowNull: false,
      unique: true,
    },
    user_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      references: { model: 'users', key: 'id' },
    },
    status: {
      type: DataTypes.ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'),
      defaultValue: 'pending',
    },
    subtotal: {
      type: DataTypes.DECIMAL(12, 2),
      allowNull: false,
      defaultValue: 0,
    },
    discount_amount: {
      type: DataTypes.DECIMAL(12, 2),
      defaultValue: 0,
    },
    shipping_cost: {
      type: DataTypes.DECIMAL(12, 2),
      defaultValue: 0,
    },
    total: {
      type: DataTypes.DECIMAL(12, 2),
      allowNull: false,
      defaultValue: 0,
    },
    shipping_name: {
      type: DataTypes.STRING(100),
      allowNull: true,
    },
    shipping_phone: {
      type: DataTypes.STRING(20),
      allowNull: true,
    },
    shipping_address: {
      type: DataTypes.TEXT,
      allowNull: true,
    },
    shipping_city: {
      type: DataTypes.STRING(100),
      allowNull: true,
    },
    notes: {
      type: DataTypes.TEXT,
      allowNull: true,
    },
    coupon_id: {
      type: DataTypes.INTEGER,
      allowNull: true,
      references: { model: 'coupons', key: 'id' },
    },
  }, {
    tableName: 'orders',
    indexes: [
      { fields: ['order_number'], unique: true },
      { fields: ['user_id'] },
      { fields: ['status'] },
    ],
  });

  Order.associate = (models) => {
    Order.belongsTo(models.User, { foreignKey: 'user_id' });
    Order.hasMany(models.OrderItem, { foreignKey: 'order_id' });
    Order.hasOne(models.Payment, { foreignKey: 'order_id' });
    Order.belongsTo(models.Coupon, { foreignKey: 'coupon_id' });
  };

  return Order;
};
