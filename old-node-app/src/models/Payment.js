'use strict';

module.exports = (sequelize, DataTypes) => {
  const Payment = sequelize.define('Payment', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    order_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      unique: true,
      references: { model: 'orders', key: 'id' },
    },
    method: {
      type: DataTypes.ENUM('bkash', 'nagad', 'visa', 'mastercard', 'cod'),
      allowNull: false,
    },
    status: {
      type: DataTypes.ENUM('pending', 'completed', 'failed', 'refunded'),
      defaultValue: 'pending',
    },
    amount: {
      type: DataTypes.DECIMAL(12, 2),
      allowNull: false,
    },
    transaction_id: {
      type: DataTypes.STRING(255),
      allowNull: true,
    },
    paid_at: {
      type: DataTypes.DATE,
      allowNull: true,
    },
  }, {
    tableName: 'payments',
    indexes: [
      { fields: ['order_id'], unique: true },
      { fields: ['transaction_id'] },
    ],
  });

  Payment.associate = (models) => {
    Payment.belongsTo(models.Order, { foreignKey: 'order_id' });
  };

  return Payment;
};
