'use strict';

module.exports = (sequelize, DataTypes) => {
  const Cart = sequelize.define('Cart', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true,
    },
    user_id: {
      type: DataTypes.INTEGER,
      allowNull: false,
      unique: true,
      references: { model: 'users', key: 'id' },
    },
  }, {
    tableName: 'carts',
    indexes: [
      { fields: ['user_id'], unique: true },
    ],
  });

  Cart.associate = (models) => {
    Cart.belongsTo(models.User, { foreignKey: 'user_id' });
    Cart.hasMany(models.CartItem, { foreignKey: 'cart_id' });
  };

  return Cart;
};
