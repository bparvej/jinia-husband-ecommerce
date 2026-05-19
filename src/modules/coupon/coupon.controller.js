const couponService = require('./coupon.service');
const { getPageNumbers } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class CouponController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search } = req.query;
      const { coupons, pagination } = await couponService.getCoupons(page, 12, { search });

      const viewData = {
        layout: 'layouts/admin',
        title: 'Coupons — HomeI Admin',
        coupons,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { search },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/coupons/partials/coupon-table', { ...viewData, layout: false });
      }

      res.render('admin/coupons/index', viewData);
    } catch (err) {
      logger.error('Coupon list error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async store(req, res) {
    try {
      await couponService.createCoupon(req.body);
      if (req.headers['hx-request']) {
        return res.set('HX-Redirect', '/admin/coupons').send('');
      }
      res.redirect('/admin/coupons');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async update(req, res) {
    try {
      await couponService.updateCoupon(req.params.id, req.body);
      if (req.headers['hx-request']) {
        return res.set('HX-Redirect', '/admin/coupons').send('');
      }
      res.redirect('/admin/coupons');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async delete(req, res) {
    try {
      await couponService.deleteCoupon(req.params.id);
      if (req.headers['hx-request']) {
        return res.send('');
      }
      res.redirect('/admin/coupons');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new CouponController();
