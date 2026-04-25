const db = require('../../models');
const { Op } = require('sequelize');

class ProductRepository {
  async findAll({ offset = 0, limit = 12, where = {}, order = [['created_at', 'DESC']] } = {}) {
    return db.Product.findAndCountAll({
      where: { ...where, is_active: true },
      include: [
        { model: db.Category, attributes: ['id', 'name', 'slug'] },
        { model: db.Inventory, attributes: ['quantity', 'low_stock_threshold'] },
      ],
      order,
      offset,
      limit,
      distinct: true,
    });
  }

  async findAllAdmin({ offset = 0, limit = 12, where = {}, order = [['created_at', 'DESC']] } = {}) {
    return db.Product.findAndCountAll({
      where,
      include: [
        { model: db.Category, attributes: ['id', 'name', 'slug'] },
        { model: db.Inventory, attributes: ['quantity', 'low_stock_threshold'] },
      ],
      order,
      offset,
      limit,
      distinct: true,
    });
  }

  async findById(id) {
    return db.Product.findByPk(id, {
      include: [
        { model: db.Category },
        { model: db.Inventory },
        { model: db.ProductVariant },
      ],
    });
  }

  async findBySlug(slug) {
    return db.Product.findOne({
      where: { slug, is_active: true },
      include: [
        { model: db.Category },
        { model: db.Inventory },
        { model: db.ProductVariant },
        { model: db.Review, include: [{ model: db.User, attributes: ['name'] }] },
      ],
      distinct: true,
    });
  }

  async create(data) {
    return db.Product.create(data);
  }

  async update(id, data, options = {}) {
    const product = await db.Product.findByPk(id, options);
    if (!product) throw new Error('Product not found');
    return product.update(data, options);
  }

  async delete(id) {
    const product = await db.Product.findByPk(id);
    if (!product) throw new Error('Product not found');
    return product.destroy();
  }

  async search(query, { offset = 0, limit = 12 } = {}) {
    return db.Product.findAndCountAll({
      where: {
        is_active: true,
        [Op.or]: [
          { name: { [Op.iLike]: `%${query}%` } },
          { description: { [Op.iLike]: `%${query}%` } },
          { sku: { [Op.iLike]: `%${query}%` } },
        ],
      },
      include: [
        { model: db.Category, attributes: ['id', 'name', 'slug'] },
        { model: db.Inventory, attributes: ['quantity', 'low_stock_threshold'] },
      ],
      order: [['created_at', 'DESC']],
      offset,
      limit,
      distinct: true,
    });
  }

  async getFeatured(limit = 8) {
    return db.Product.findAll({
      where: { is_active: true, is_featured: true },
      include: [
        { model: db.Category, attributes: ['id', 'name', 'slug'] },
      ],
      order: [['sold_count', 'DESC']],
      limit,
    });
  }

  async getByCategory(categoryId, { offset = 0, limit = 12 } = {}) {
    return db.Product.findAndCountAll({
      where: { category_id: categoryId, is_active: true },
      include: [
        { model: db.Category, attributes: ['id', 'name', 'slug'] },
      ],
      order: [['created_at', 'DESC']],
      offset,
      limit,
      distinct: true,
    });
  }

  async count(where = {}) {
    return db.Product.count({ where });
  }

  async incrementSoldCount(id, amount, transaction = null) {
    const opts = transaction ? { transaction } : {};
    const product = await db.Product.findByPk(id, opts);
    if (!product) throw new Error('Product not found');
    return product.increment('sold_count', { by: amount, ...opts });
  }

  async findByPk(id, { transaction } = {}) {
    return db.Product.findByPk(id, { transaction });
  }

  async findAllCategories() {
    return db.Category.findAll({
      where: { is_active: true },
      order: [['sort_order', 'ASC']],
    });
  }
}

module.exports = new ProductRepository();
