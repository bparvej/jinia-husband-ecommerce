const reportService = require('./report.service');
const logger = require('../../utils/logger');

class ReportController {
  async adminIndex(req, res) {
    try {
      const stats = await reportService.getDashboardStats();
      res.render('admin/reports/index', {
        layout: 'layouts/admin',
        title: 'Reports & Analytics — HomeI Admin',
        stats,
      });
    } catch (err) {
      logger.error('Report dashboard error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async getSalesReport(req, res) {
    try {
      const { range } = req.query;
      const data = await reportService.getSalesReport(range);
      
      if (req.headers['hx-request']) {
        return res.render('admin/reports/partials/sales-report', { data, layout: false });
      }
      
      res.json(data);
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  }
}

module.exports = new ReportController();
