'use strict';

module.exports = (sequelize, DataTypes) => {
  const CartItem = sequelize.define('CartItem', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    cart_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      references: { model: 'carts', key: 'id' },
    },
    product_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      references: { model: 'products', key: 'id' },
    },
    variant_id: {
      type: DataTypes.INTEGER,
      allowNull: true,
      references: { model: 'product_variants', key: 'id' },
    },
    quantity: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 1,
      validate: { min: 1 },
    },
  }, {
    tableName: 'cart_items',
    indexes: [
      { fields: ['cart_id'] },
      { fields: ['product_id'] },
    ],
  });

  CartItem.associate = (models) => {
    CartItem.belongsTo(models.Cart, { foreignKey: 'cart_id' });
    CartItem.belongsTo(models.Product, { foreignKey: 'product_id' });
    CartItem.belongsTo(models.ProductVariant, { foreignKey: 'variant_id' });
  };

  return CartItem;
};
