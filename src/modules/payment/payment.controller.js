const paymentService = require('./payment.service');
const { getPageNumbers } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class PaymentController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { status, method } = req.query;
      
      // We might need to add a listPayments method to the service if it doesn't exist
      // For now, let's assume we can fetch them via a repository method or similar
      const paymentRepository = require('./payment.repository');
      const { paginate } = require('../../utils/pagination');
      
      const where = {};
      if (status) where.status = status;
      if (method) where.method = method;
      
      const total = await paymentRepository.count({ where });
      const pagination = paginate(page, 12, total);
      const payments = await paymentRepository.findAll({
        where,
        offset: pagination.offset,
        limit: pagination.limit,
        include: [{ model: require('../../models').Order, attributes: ['order_number'] }]
      });

      const viewData = {
        layout: 'layouts/admin',
        title: 'Payments — HomeI Admin',
        payments: payments.rows,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { status, method },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/payments/partials/payment-table', { ...viewData, layout: false });
      }

      res.render('admin/payments/index', viewData);
    } catch (err) {
      logger.error('Payment list error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async updateStatus(req, res) {
    try {
      const { status, transaction_id } = req.body;
      await paymentService.updatePaymentStatus(req.params.orderId, status, transaction_id);
      
      if (req.headers['hx-request']) {
        return res.send(`
          <select class="status-select status-${status}" 
                  name="status"
                  hx-put="/api/v1/payments/${req.params.orderId}/status"
                  hx-target="this"
                  hx-swap="outerHTML"
                  hx-trigger="change">
              <option value="pending" ${status === 'pending' ? 'selected' : ''}>Pending</option>
              <option value="completed" ${status === 'completed' ? 'selected' : ''}>Completed</option>
              <option value="failed" ${status === 'failed' ? 'selected' : ''}>Failed</option>
              <option value="refunded" ${status === 'refunded' ? 'selected' : ''}>Refunded</option>
          </select>
          <div hx-swap-oob="beforeend:#toast-container">
            <div class="toast toast-success">Payment status updated to ${status}</div>
          </div>
        `);
      }
      res.redirect('back');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new PaymentController();
