const orderService = require('./order.service');
const { getPageNumbers } = require('../../utils/pagination');

class OrderController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search, status } = req.query;
      const { orders, pagination } = await orderService.getOrders(page, 12, { search, status });

      const viewData = {
        layout: 'layouts/admin',
        title: 'Orders — HomeI Admin',
        orders,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { search, status },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/orders/partials/order-table', { ...viewData, layout: false });
      }

      res.render('admin/orders/index', viewData);
    } catch (err) {
      throw err;
    }
  }

  async adminDetail(req, res) {
    try {
      const order = await orderService.getOrderById(req.params.id);
      res.render('admin/orders/detail', {
        layout: 'layouts/admin',
        title: `Order ${order.order_number} — HomeI Admin`,
        order,
      });
    } catch (err) {
      res.redirect('/admin/orders');
    }
  }

  async updateStatus(req, res) {
    try {
      const { status } = req.body;
      await orderService.updateOrderStatus(req.params.id, status);

      if (req.headers['hx-request']) {
        const order = await orderService.getOrderById(req.params.id);
        return res.render('admin/orders/partials/status-badge', { order, layout: false });
      }
      res.redirect(`/admin/orders/${req.params.id}`);
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async dashboard(req, res) {
    try {
      const reportService = require('../report/report.service');
      const dashboardData = await orderService.getDashboardStats();
      const reportStats = await reportService.getDashboardStats();

      const viewData = {
        layout: 'layouts/admin',
        title: 'Dashboard — HomeI Admin',
        ...reportStats,
        stats: dashboardData.stats,
        recentOrders: dashboardData.recentOrders,
        lowStock: dashboardData.lowStock,
      };

      if (req.headers['hx-request'] && req.query._partial === 'stats') {
        return res.render('admin/dashboard/partials/stats-cards', { ...viewData, layout: false });
      }

      res.render('admin/dashboard', viewData);
    } catch (err) {
      res.status(500).render('pages/error', {
        layout: 'layouts/admin',
        title: 'Error',
        message: err.message,
        error: err,
      });
    }
  }

  async placeOrder(req, res) {
    const cartService = require('../cart/cart.service');
    const bcrypt = require('bcryptjs');
    const db = require('../../models');

    try {
      const { shipping_name, shipping_phone, shipping_address, shipping_city, payment_method } = req.body;
      
      req.session.cart = req.session.cart || { items: [] };
      const cart = await cartService.getCart(req.session.userId, req.session.cart);

      if (!cart || !cart.CartItems || cart.CartItems.length === 0) {
        if (req.headers['hx-request']) {
          return res.status(400).send('<div class="toast toast-error">Your cart is empty</div>');
        }
        return res.status(400).json({ error: 'Your cart is empty' });
      }

      let userId = req.session.userId;

      // Handle Guest Checkout
      if (!userId) {
        let user = await db.User.findOne({ where: { phone: shipping_phone } });
        if (!user) {
          const cleanPhone = shipping_phone.replace(/\s+/g, '');
          const guestEmail = `guest_${cleanPhone}@homei.com.bd`;
          user = await db.User.findOne({ where: { email: guestEmail } });

          if (!user) {
            const role = await db.Role.findOne({ where: { name: 'customer' } });
            const roleId = role ? role.id : 4;
            const dummyPassword = await bcrypt.hash('Guest@' + Math.random().toString(36).slice(-6) + '2026', 12);

            user = await db.User.create({
              name: shipping_name,
              email: guestEmail,
              password: dummyPassword,
              phone: shipping_phone,
              role_id: roleId,
              is_active: true
            });
          }
        }
        userId = user.id;
      }

      const shippingData = {
        shipping_name,
        shipping_phone,
        shipping_address,
        shipping_city,
        payment_method: payment_method || 'cod'
      };

      // Create Order
      const order = await orderService.createOrder(userId, cart.CartItems, shippingData);

      // Clear Cart
      await cartService.clearCart(req.session.userId, req.session.cart);

      // Get Detailed Order (with payments and items loaded)
      const detailedOrder = await orderService.getOrderById(order.id);

      if (req.headers['hx-request']) {
        return res.render('partials/checkout-success', {
          layout: false,
          order: detailedOrder
        });
      }

      res.json({ success: true, order: detailedOrder });
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<div class="toast toast-error">Checkout failed: ${err.message}</div>`);
      }
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new OrderController();
