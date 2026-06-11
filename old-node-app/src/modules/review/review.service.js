const reviewRepository = require('./review.repository');
const productRepository = require('../product/product.repository');
const { paginate } = require('../../utils/pagination');
const logger = require('../../utils/logger');
const db = require('../../models');

class ReviewService {
  async getReviews(page = 1, limit = 12, filters = {}) {
    const where = {};
    if (filters.product_id) where.product_id = filters.product_id;
    if (filters.is_approved !== undefined) where.is_approved = filters.is_approved;

    const total = await reviewRepository.count({ where });
    const pagination = paginate(page, limit, total);
    const result = await reviewRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { reviews: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async approveReview(id) {
    const transaction = await db.sequelize.transaction();
    try {
      const review = await reviewRepository.update(id, { is_approved: true }, { transaction });
      
      // Update product average rating
      const stats = await reviewRepository.getAverageRating(review.product_id, transaction);
      await productRepository.update(review.product_id, {
        avg_rating: stats.avg_rating || 0,
        review_count: stats.review_count || 0,
      }, { transaction });

      await transaction.commit();
      logger.info('Review approved', { reviewId: id });
      return review;
    } catch (err) {
      await transaction.rollback();
      throw err;
    }
  }

  async deleteReview(id) {
    await reviewRepository.delete(id);
    logger.info('Review deleted', { reviewId: id });
  }
}

module.exports = new ReviewService();
