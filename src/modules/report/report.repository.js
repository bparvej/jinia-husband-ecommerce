const db = require('../../models');
const { Op } = require('sequelize');

class ReportRepository {
  async getSalesReport(startDate, endDate) {
    return db.Order.findAll({
      where: {
        status: { [Op.notIn]: ['cancelled', 'refunded'] },
        created_at: { [Op.between]: [startDate, endDate] }
      },
      attributes: [
        [db.sequelize.fn('DATE', db.sequelize.col('created_at')), 'date'],
        [db.sequelize.fn('SUM', db.sequelize.col('total')), 'revenue'],
        [db.sequelize.fn('COUNT', db.sequelize.col('id')), 'orders']
      ],
      group: [db.sequelize.fn('DATE', db.sequelize.col('created_at'))],
      order: [[db.sequelize.fn('DATE', db.sequelize.col('created_at')), 'ASC']],
      raw: true
    });
  }

  async getTopProducts(startDate, endDate, limit = 5) {
    return db.OrderItem.findAll({
      where: {
        created_at: { [Op.between]: [startDate, endDate] }
      },
      attributes: [
        'product_name',
        [db.sequelize.fn('SUM', db.sequelize.col('quantity')), 'quantity'],
        [db.sequelize.fn('SUM', db.sequelize.col('total_price')), 'revenue']
      ],
      group: ['product_name'],
      order: [[db.sequelize.fn('SUM', db.sequelize.col('quantity')), 'DESC']],
      limit,
      raw: true
    });
  }

  async getCategorySales(startDate, endDate) {
    // This requires joining with Product and Category
    return db.OrderItem.findAll({
      where: {
        created_at: { [Op.between]: [startDate, endDate] }
      },
      include: [{
        model: db.Product,
        include: [{ model: db.Category, attributes: ['name'] }]
      }],
      attributes: [
        [db.sequelize.col('Product.Category.name'), 'category_name'],
        [db.sequelize.fn('SUM', db.sequelize.col('order_items.total_price')), 'revenue']
      ],
      group: [db.sequelize.col('Product.Category.name')],
      raw: true
    });
  }
}

module.exports = new ReportRepository();
