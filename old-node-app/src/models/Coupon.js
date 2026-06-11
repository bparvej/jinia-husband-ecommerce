'use strict';

module.exports = (sequelize, DataTypes) => {
  const Coupon = sequelize.define('Coupon', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    code: {
      type: DataTypes.STRING(50),
      allowNull: false,
      unique: true,
    },
    description: {
      type: DataTypes.STRING(255),
      allowNull: true,
    },
    discount_type: {
      type: DataTypes.ENUM('percentage', 'fixed'),
      allowNull: false,
    },
    discount_value: {
      type: DataTypes.DECIMAL(12, 2),
      allowNull: false,
    },
    min_order_amount: {
      type: DataTypes.DECIMAL(12, 2),
      defaultValue: 0,
    },
    max_discount: {
      type: DataTypes.DECIMAL(12, 2),
      allowNull: true,
    },
    usage_limit: {
      type: DataTypes.INTEGER,
      allowNull: true,
    },
    used_count: {
      type: DataTypes.INTEGER,
      defaultValue: 0,
    },
    starts_at: {
      type: DataTypes.DATE,
      allowNull: true,
    },
    expires_at: {
      type: DataTypes.DATE,
      allowNull: true,
    },
    is_active: {
      type: DataTypes.BOOLEAN,
      defaultValue: true,
    },
  }, {
    tableName: 'coupons',
    indexes: [
      { fields: ['code'], unique: true },
    ],
  });

  Coupon.associate = (models) => {
    Coupon.hasMany(models.Order, { foreignKey: 'coupon_id' });
  };

  return Coupon;
};
