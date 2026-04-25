const inventoryService = require('./inventory.service');
const { getPageNumbers } = require('../../utils/pagination');

class InventoryController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search } = req.query;
      const { inventory, pagination } = await inventoryService.getInventory(page, 20, { search });

      const viewData = {
        layout: 'layouts/admin',
        title: 'Inventory — HomeI Admin',
        inventory,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { search },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/inventory/partials/inventory-table', viewData);
      }

      res.render('admin/inventory/index', viewData);
    } catch (err) {
      throw err;
    }
  }

  async updateStock(req, res) {
    try {
      const { product_id, quantity } = req.body;
      await inventoryService.updateStock(product_id || req.params.id, quantity);

      if (req.headers['hx-request']) {
        return res.send('<span class="toast toast-success">Stock updated!</span>');
      }
      res.redirect('/admin/inventory');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new InventoryController();
