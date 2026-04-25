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
        return res.render('admin/orders/partials/order-table', viewData);
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
        return res.render('admin/orders/partials/status-badge', { order });
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
        return res.render('admin/dashboard/partials/stats-cards', viewData);
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
}

module.exports = new OrderController();
