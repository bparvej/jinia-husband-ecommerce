const shippingService = require('./shipping.service');
const { getPageNumbers } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class ShippingController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { status, city } = req.query;
      const { shipments, pagination } = await shippingService.getShipments(page, 12, { status, city });

      const viewData = {
        layout: 'layouts/admin',
        title: 'Shipping — HomeI Admin',
        shipments,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { status, city },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/shipping/partials/shipping-table', { ...viewData, layout: false });
      }

      res.render('admin/shipping/index', viewData);
    } catch (err) {
      logger.error('Shipping list error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async updateStatus(req, res) {
    try {
      const { status, tracking_number } = req.body;
      await shippingService.updateShippingStatus(req.params.id, status, tracking_number);
      
      if (req.headers['hx-request']) {
        return res.send(`
          <select class="status-select status-${status}" 
                  name="status"
                  hx-put="/admin/shipping/${req.params.id}/status"
                  hx-target="this"
                  hx-swap="outerHTML"
                  hx-trigger="change">
              <option value="pending" ${status === 'pending' ? 'selected' : ''}>Pending</option>
              <option value="shipped" ${status === 'shipped' ? 'selected' : ''}>Shipped</option>
              <option value="delivered" ${status === 'delivered' ? 'selected' : ''}>Delivered</option>
              <option value="cancelled" ${status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
          </select>
          <div hx-swap-oob="beforeend:#toast-container">
            <div class="toast toast-success">Shipping status updated to ${status}</div>
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

module.exports = new ShippingController();
