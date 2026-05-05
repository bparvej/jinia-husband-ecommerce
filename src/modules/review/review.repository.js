const db = require('../../models');

class ReviewRepository {
  async findAll({ offset = 0, limit = 12, where = {} } = {}) {
    return db.Review.findAndCountAll({
      where,
      include: [
        { model: db.User, attributes: ['id', 'name'] },
        { model: db.Product, attributes: ['id', 'name'] },
      ],
      order: [['created_at', 'DESC']],
      offset,
      limit,
    });
  }

  async create(data) {
    return db.Review.create(data);
  }

  async update(id, data) {
    const review = await db.Review.findByPk(id);
    if (!review) throw new Error('Review not found');
    return review.update(data);
  }

  async delete(id) {
    const review = await db.Review.findByPk(id);
    if (!review) throw new Error('Review not found');
    return review.destroy();
  }

  async count(options = {}) {
    return db.Review.count(options);
  }

  async getAverageRating(productId) {
    const result = await db.Review.findOne({
      where: { product_id: productId, is_active: true },
      attributes: [
        [db.sequelize.fn('AVG', db.sequelize.col('rating')), 'avg_rating'],
        [db.sequelize.fn('COUNT', db.sequelize.col('id')), 'review_count'],
      ],
      raw: true,
    });
    return result;
  }
}

module.exports = new ReviewRepository();
