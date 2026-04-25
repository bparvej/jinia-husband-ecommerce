const { AbilityBuilder, createMongoAbility } = require('@casl/ability');

/**
 * Define CASL abilities based on user role
 * Subjects: User, Product, Category, Order, Inventory, Cart, Payment, Coupon, Review, AuditLog
 * Actions: create, read, update, delete, manage
 */
function defineAbilitiesFor(user) {
  const { can, cannot, build } = new AbilityBuilder(createMongoAbility);

  if (!user || !user.Role) {
    // Guest — can only read products and categories
    can('read', 'Product');
    can('read', 'Category');
    return build();
  }

  const roleName = user.Role.name;

  switch (roleName) {
    case 'super_admin':
      can('manage', 'all');
      break;

    case 'admin':
      can('manage', 'Product');
      can('manage', 'Category');
      can('manage', 'Order');
      can('manage', 'Inventory');
      can('manage', 'Payment');
      can('manage', 'Coupon');
      can('manage', 'Review');
      can('read', 'User');
      can('read', 'AuditLog');
      can('read', 'Report');
      break;

    case 'manager':
      can('read', 'Product');
      can('update', 'Product');
      can('read', 'Category');
      can('read', 'Order');
      can('update', 'Order');
      can('read', 'Inventory');
      can('update', 'Inventory');
      can('read', 'Review');
      break;

    case 'customer':
      can('read', 'Product');
      can('read', 'Category');
      can('manage', 'Cart', { user_id: user.id });
      can('create', 'Order');
      can('read', 'Order', { user_id: user.id });
      can('create', 'Review');
      can('read', 'Review');
      can('update', 'Review', { user_id: user.id });
      can('delete', 'Review', { user_id: user.id });
      break;

    default:
      can('read', 'Product');
      can('read', 'Category');
      break;
  }

  return build();
}

module.exports = { defineAbilitiesFor };
