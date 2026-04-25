const reviewService = require('./review.service');
const { getPageNumbers } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class ReviewController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { is_approved, product_id } = req.query;
      const filters = { product_id };
      if (is_approved !== undefined && is_approved !== '') {
        filters.is_approved = is_approved === 'true';
      }

      const { reviews, pagination } = await reviewService.getReviews(page, 12, filters);

      const viewData = {
        layout: 'layouts/admin',
        title: 'Reviews — HomeI Admin',
        reviews,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { is_approved, product_id },
      };

      if (req.headers['hx-request']) {
        return res.render('admin/reviews/partials/review-table', viewData);
      }

      res.render('admin/reviews/index', viewData);
    } catch (err) {
      logger.error('Review list error', { error: err.message });
      res.status(500).send(err.message);
    }
  }

  async approve(req, res) {
    try {
      await reviewService.approveReview(req.params.id);
      if (req.headers['hx-request']) {
        return res.send('<span class="status-badge active">Approved</span>');
      }
      res.redirect('back');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async delete(req, res) {
    try {
      await reviewService.deleteReview(req.params.id);
      if (req.headers['hx-request']) {
        return res.send('');
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

module.exports = new ReviewController();
