'use strict';
const bcrypt = require('bcryptjs');

module.exports = {
  async up(queryInterface) {
    // ============================
    // 0. CHECK IF ALREADY SEEDED
    // ============================
    const [existingRoles] = await queryInterface.sequelize.query('SELECT id FROM roles LIMIT 1');
    if (existingRoles.length > 0) {
      console.log('Database already seeded. Skipping seed execution.');
      return;
    }

    // ============================
    // 1. ROLES
    // ============================
    const roles = [
      { name: 'super_admin', description: 'Full system access', created_at: new Date(), updated_at: new Date() },
      { name: 'admin', description: 'Manage products, orders, inventory', created_at: new Date(), updated_at: new Date() },
      { name: 'manager', description: 'Limited management access', created_at: new Date(), updated_at: new Date() },
      { name: 'customer', description: 'Shop and place orders', created_at: new Date(), updated_at: new Date() },
    ];
    await queryInterface.bulkInsert('roles', roles);

    // ============================
    // 2. PERMISSIONS
    // ============================
    const subjects = ['User', 'Product', 'Category', 'Order', 'Inventory', 'Payment', 'Coupon', 'Review', 'Cart', 'AuditLog', 'Report'];
    const actions = ['create', 'read', 'update', 'delete'];
    const permissions = [];
    for (const subject of subjects) {
      for (const action of actions) {
        permissions.push({
          action,
          subject,
          description: `${action} ${subject}`,
          created_at: new Date(),
          updated_at: new Date(),
        });
      }
    }
    // Add manage all permission
    permissions.push({
      action: 'manage',
      subject: 'all',
      description: 'Full access to everything',
      created_at: new Date(),
      updated_at: new Date(),
    });
    await queryInterface.bulkInsert('permissions', permissions);

    // ============================
    // 3. ROLE_PERMISSIONS
    // ============================
    // Get the inserted roles and permissions
    const [rolesDb] = await queryInterface.sequelize.query('SELECT id, name FROM roles');
    const [permsDb] = await queryInterface.sequelize.query('SELECT id, action, subject FROM permissions');

    const roleMap = {};
    rolesDb.forEach(r => { roleMap[r.name] = r.id; });

    const permMap = {};
    permsDb.forEach(p => { permMap[`${p.action}:${p.subject}`] = p.id; });

    const rolePerms = [];
    const now = new Date();

    // super_admin gets manage:all
    rolePerms.push({ role_id: roleMap['super_admin'], permission_id: permMap['manage:all'], created_at: now, updated_at: now });

    // admin gets everything except User delete and AuditLog write
    const adminSubjects = ['Product', 'Category', 'Order', 'Inventory', 'Payment', 'Coupon', 'Review'];
    for (const subj of adminSubjects) {
      for (const act of actions) {
        if (permMap[`${act}:${subj}`]) {
          rolePerms.push({ role_id: roleMap['admin'], permission_id: permMap[`${act}:${subj}`], created_at: now, updated_at: now });
        }
      }
    }
    rolePerms.push({ role_id: roleMap['admin'], permission_id: permMap['read:User'], created_at: now, updated_at: now });
    rolePerms.push({ role_id: roleMap['admin'], permission_id: permMap['read:AuditLog'], created_at: now, updated_at: now });
    rolePerms.push({ role_id: roleMap['admin'], permission_id: permMap['read:Report'], created_at: now, updated_at: now });

    // manager: read/update Product, Category, Order, Inventory, read Review
    const managerPerms = [
      'read:Product', 'update:Product', 'read:Category', 'read:Order', 'update:Order',
      'read:Inventory', 'update:Inventory', 'read:Review',
    ];
    for (const perm of managerPerms) {
      if (permMap[perm]) {
        rolePerms.push({ role_id: roleMap['manager'], permission_id: permMap[perm], created_at: now, updated_at: now });
      }
    }

    // customer: read Product/Category, CRUD Cart, create/read Order, CRUD own Review
    const customerPerms = [
      'read:Product', 'read:Category', 'create:Cart', 'read:Cart', 'update:Cart', 'delete:Cart',
      'create:Order', 'read:Order', 'create:Review', 'read:Review', 'update:Review', 'delete:Review',
    ];
    for (const perm of customerPerms) {
      if (permMap[perm]) {
        rolePerms.push({ role_id: roleMap['customer'], permission_id: permMap[perm], created_at: now, updated_at: now });
      }
    }

    await queryInterface.bulkInsert('role_permissions', rolePerms);

    // ============================
    // 4. SUPER ADMIN USER
    // ============================
    const hashedPassword = await bcrypt.hash('Admin@123', 12);
    await queryInterface.bulkInsert('users', [
      {
        name: 'Super Admin',
        email: 'admin@homei.com',
        password: hashedPassword,
        phone: '+880 1XXX-XXXXXX',
        role_id: roleMap['super_admin'],
        is_active: true,
        created_at: now,
        updated_at: now,
      },
      {
        name: 'Demo Customer',
        email: 'customer@homei.com',
        password: await bcrypt.hash('Customer@123', 12),
        phone: '+880 1XXX-XXXXXX',
        role_id: roleMap['customer'],
        is_active: true,
        created_at: now,
        updated_at: now,
      },
    ]);

    // ============================
    // 5. CATEGORIES
    // ============================
    const categories = [
      { name: 'Bookshelves', slug: 'bookshelves', description: 'Wooden bookshelves and display units', image: '/assets/images/category-bookshelf.png', sort_order: 1, is_active: true, created_at: now, updated_at: now },
      { name: 'Dining', slug: 'dining', description: 'Dining tables and chair sets', image: '/assets/images/category-dining.png', sort_order: 2, is_active: true, created_at: now, updated_at: now },
      { name: 'Bedroom', slug: 'bedroom', description: 'Bed frames, nightstands, and wardrobes', image: '/assets/images/category-bedroom.png', sort_order: 3, is_active: true, created_at: now, updated_at: now },
      { name: 'Storage', slug: 'storage', description: 'TV units, cabinets, and storage solutions', image: '/assets/images/category-storage.png', sort_order: 4, is_active: true, created_at: now, updated_at: now },
      { name: 'Study & Office', slug: 'study-office', description: 'Study desks and office furniture', image: '/assets/images/category-desk.png', sort_order: 5, is_active: true, created_at: now, updated_at: now },
      { name: 'Home Décor', slug: 'home-decor', description: 'Decorative items and accessories', image: '/assets/images/hero-living-room.png', sort_order: 6, is_active: true, created_at: now, updated_at: now },
    ];
    await queryInterface.bulkInsert('categories', categories);

    // ============================
    // 6. SAMPLE PRODUCTS
    // ============================
    const [catsDb] = await queryInterface.sequelize.query('SELECT id, slug FROM categories');
    const catMap = {};
    catsDb.forEach(c => { catMap[c.slug] = c.id; });

    const products = [
      {
        name: 'Nordic Oak Ladder Shelf', slug: 'nordic-oak-ladder-shelf', description: 'A beautifully crafted 5-tier ladder shelf made from premium Nordic Oak. Perfect for displaying books, plants, and decorative items.', short_description: 'Handcrafted 5-tier oak ladder bookshelf',
        price: 8500, compare_price: 10200, cost_price: 5500, sku: 'Hi-BS-001', category_id: catMap['bookshelves'],
        image: '/assets/images/category-bookshelf.png', badge: 'New', is_active: true, is_featured: true, avg_rating: 4.90, review_count: 48, sold_count: 342,
        created_at: now, updated_at: now,
      },
      {
        name: 'Walnut Dining Set — 4 Seater', slug: 'walnut-dining-set-4-seater', description: 'Elegant walnut wood dining table with 4 matching chairs. Seats 4 comfortably with a smooth lacquer finish.', short_description: '4-seater walnut dining table with chairs',
        price: 28000, compare_price: 32500, cost_price: 18000, sku: 'Hi-DN-001', category_id: catMap['dining'],
        image: '/assets/images/category-dining.png', badge: 'Hot', is_active: true, is_featured: true, avg_rating: 4.95, review_count: 72, sold_count: 278,
        created_at: now, updated_at: now,
      },
      {
        name: 'Oak Slatted Bed Frame — King', slug: 'oak-slatted-bed-frame-king', description: 'Solid oak king-size bed frame with a slatted design for modern minimalist bedrooms. Durable and elegant.', short_description: 'King-size solid oak slatted bed frame',
        price: 35000, compare_price: null, cost_price: 22000, sku: 'Hi-BR-001', category_id: catMap['bedroom'],
        image: '/assets/images/category-bedroom.png', badge: 'New', is_active: true, is_featured: true, avg_rating: 4.20, review_count: 35, sold_count: 156,
        created_at: now, updated_at: now,
      },
      {
        name: 'Minimalist Oak TV Console', slug: 'minimalist-oak-tv-console', description: 'Sleek, modern TV console crafted from solid oak with cable management. Fits TVs up to 65 inches.', short_description: 'Minimalist oak TV console for modern living rooms',
        price: 18500, compare_price: 22000, cost_price: 12000, sku: 'Hi-ST-001', category_id: catMap['storage'],
        image: '/assets/images/category-storage.png', badge: 'Sale', is_active: true, is_featured: true, avg_rating: 4.95, review_count: 56, sold_count: 215,
        created_at: now, updated_at: now,
      },
      {
        name: 'Cane-Woven Writing Desk', slug: 'cane-woven-writing-desk', description: 'Elegant writing desk featuring a cane-woven drawer front. Compact design perfect for home offices and study rooms.', short_description: 'Compact cane-woven writing desk',
        price: 14200, compare_price: null, cost_price: 9000, sku: 'Hi-SD-001', category_id: catMap['study-office'],
        image: '/assets/images/category-desk.png', badge: 'New', is_active: true, is_featured: false, avg_rating: 4.95, review_count: 29, sold_count: 98,
        created_at: now, updated_at: now,
      },
      {
        name: 'Wall-Mounted Floating Shelf Set', slug: 'wall-mounted-floating-shelf-set', description: 'Set of 3 floating shelves in different sizes. Easy to mount and perfect for any wall space.', short_description: '3-piece wall-mounted floating shelf set',
        price: 4800, compare_price: 6000, cost_price: 2800, sku: 'Hi-BS-002', category_id: catMap['bookshelves'],
        image: '/assets/images/category-bookshelf.png', badge: 'Best Seller', is_active: true, is_featured: true, avg_rating: 5.00, review_count: 94, sold_count: 420,
        created_at: now, updated_at: now,
      },
      {
        name: 'Oak Bedside Nightstand', slug: 'oak-bedside-nightstand', description: 'Compact bedside table with a single drawer and open shelf. Matches our Oak bed frame collection.', short_description: 'Oak bedside nightstand with drawer',
        price: 6500, compare_price: null, cost_price: 4000, sku: 'Hi-BR-002', category_id: catMap['bedroom'],
        image: '/assets/images/category-bedroom.png', badge: null, is_active: true, is_featured: false, avg_rating: 4.10, review_count: 41, sold_count: 187,
        created_at: now, updated_at: now,
      },
      {
        name: 'Round Walnut Coffee Table', slug: 'round-walnut-coffee-table', description: 'Beautiful round coffee table crafted from solid walnut. Features tapered legs and a smooth finish.', short_description: 'Round walnut coffee table with tapered legs',
        price: 9800, compare_price: 12000, cost_price: 6500, sku: 'Hi-DN-002', category_id: catMap['dining'],
        image: '/assets/images/category-dining.png', badge: 'Sale', is_active: true, is_featured: false, avg_rating: 4.95, review_count: 63, sold_count: 195,
        created_at: now, updated_at: now,
      },
    ];
    await queryInterface.bulkInsert('products', products);

    // ============================
    // 7. INVENTORY
    // ============================
    const [prodsDb] = await queryInterface.sequelize.query('SELECT id FROM products ORDER BY id');
    const inventoryItems = prodsDb.map((p, i) => ({
      product_id: p.id,
      quantity: [45, 12, 28, 8, 35, 60, 22, 15][i] || 20,
      low_stock_threshold: 10,
      warehouse_location: 'Dhaka Main',
      created_at: now,
      updated_at: now,
    }));
    await queryInterface.bulkInsert('inventory', inventoryItems);

    // ============================
    // 8. SAMPLE ORDERS
    // ============================
    const [usersDb] = await queryInterface.sequelize.query("SELECT id FROM users WHERE email = 'customer@homei.com'");
    if (usersDb.length > 0) {
      const customerId = usersDb[0].id;
      await queryInterface.bulkInsert('orders', [
        {
          order_number: 'Hi-20260001',
          user_id: customerId,
          status: 'delivered',
          subtotal: 36500,
          discount_amount: 0,
          shipping_cost: 0,
          total: 36500,
          shipping_name: 'Demo Customer',
          shipping_phone: '+880 1XXX-XXXXXX',
          shipping_address: 'House 42, Road 5, Dhanmondi',
          shipping_city: 'Dhaka',
          created_at: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000),
          updated_at: now,
        },
        {
          order_number: 'Hi-20260002',
          user_id: customerId,
          status: 'processing',
          subtotal: 14200,
          discount_amount: 0,
          shipping_cost: 200,
          total: 14400,
          shipping_name: 'Demo Customer',
          shipping_phone: '+880 1XXX-XXXXXX',
          shipping_address: 'House 42, Road 5, Dhanmondi',
          shipping_city: 'Dhaka',
          created_at: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000),
          updated_at: now,
        },
        {
          order_number: 'Hi-20260003',
          user_id: customerId,
          status: 'pending',
          subtotal: 9800,
          discount_amount: 0,
          shipping_cost: 0,
          total: 9800,
          shipping_name: 'Demo Customer',
          shipping_phone: '+880 1XXX-XXXXXX',
          shipping_address: 'House 42, Road 5, Dhanmondi',
          shipping_city: 'Dhaka',
          created_at: now,
          updated_at: now,
        },
      ]);

      // Order items
      const [ordersDb] = await queryInterface.sequelize.query('SELECT id FROM orders ORDER BY id');
      const firstProductId = prodsDb[0].id;
      await queryInterface.bulkInsert('order_items', [
        { order_id: ordersDb[0].id, product_id: firstProductId, product_name: 'Nordic Oak Ladder Shelf', quantity: 1, unit_price: 8500, total_price: 8500, created_at: now, updated_at: now },
        { order_id: ordersDb[0].id, product_id: firstProductId + 1, product_name: 'Walnut Dining Set — 4 Seater', quantity: 1, unit_price: 28000, total_price: 28000, created_at: now, updated_at: now },
        { order_id: ordersDb[1].id, product_id: firstProductId + 4, product_name: 'Cane-Woven Writing Desk', quantity: 1, unit_price: 14200, total_price: 14200, created_at: now, updated_at: now },
        { order_id: ordersDb[2].id, product_id: firstProductId + 7, product_name: 'Round Walnut Coffee Table', quantity: 1, unit_price: 9800, total_price: 9800, created_at: now, updated_at: now },
      ]);

      // Payments
      await queryInterface.bulkInsert('payments', [
        { order_id: ordersDb[0].id, method: 'bkash', status: 'completed', amount: 36500, transaction_id: 'BK-2026-001', paid_at: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), created_at: now, updated_at: now },
        { order_id: ordersDb[1].id, method: 'cod', status: 'pending', amount: 14400, created_at: now, updated_at: now },
        { order_id: ordersDb[2].id, method: 'nagad', status: 'pending', amount: 9800, created_at: now, updated_at: now },
      ]);
    }
  },

  async down(queryInterface) {
    await queryInterface.bulkDelete('payments', null, {});
    await queryInterface.bulkDelete('order_items', null, {});
    await queryInterface.bulkDelete('orders', null, {});
    await queryInterface.bulkDelete('inventory', null, {});
    await queryInterface.bulkDelete('products', null, {});
    await queryInterface.bulkDelete('categories', null, {});
    await queryInterface.bulkDelete('users', null, {});
    await queryInterface.bulkDelete('role_permissions', null, {});
    await queryInterface.bulkDelete('permissions', null, {});
    await queryInterface.bulkDelete('roles', null, {});
  },
};
