'use strict';

module.exports = (sequelize, DataTypes) => {
  const Inventory = sequelize.define('Inventory', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    product_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      unique: true,
      references: { model: 'products', key: 'id' },
    },
    quantity: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 0,
      validate: { min: 0 },
    },
    low_stock_threshold: {
      type: DataTypes.INTEGER,
      defaultValue: 10,
    },
    warehouse_location: {
      type: DataTypes.STRING(100),
      allowNull: true,
    },
  }, {
    tableName: 'inventory',
    indexes: [
      { fields: ['product_id'], unique: true },
    ],
  });

  Inventory.associate = (models) => {
    Inventory.belongsTo(models.Product, { foreignKey: 'product_id' });
  };

  return Inventory;
};
