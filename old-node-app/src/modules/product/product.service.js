const productRepository = require('./product.repository');
const db = require('../../models');
const { generateSlug } = require('../../utils/validators');
const { paginate } = require('../../utils/pagination');
const logger = require('../../utils/logger');

class ProductService {
  async getProducts(page = 1, limit = 12, filters = {}) {
    const where = {};

    if (filters.category_id) {
      where.category_id = filters.category_id;
    }
    if (filters.search) {
      const { Op } = require('sequelize');
      where[Op.or] = [
        { name: { [Op.iLike]: `%${filters.search}%` } },
        { sku: { [Op.iLike]: `%${filters.search}%` } },
      ];
    }

    const countResult = await productRepository.count({ where: { ...where, is_active: true } });
    const pagination = paginate(page, limit, countResult);

    const result = await productRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { products: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async getProductsAdmin(page = 1, limit = 12, filters = {}) {
    const where = {};

    if (filters.category_id) where.category_id = filters.category_id;
    if (filters.status === 'active') where.is_active = true;
    if (filters.status === 'inactive') where.is_active = false;
    if (filters.search) {
      const { Op } = require('sequelize');
      where[Op.or] = [
        { name: { [Op.iLike]: `%${filters.search}%` } },
        { sku: { [Op.iLike]: `%${filters.search}%` } },
      ];
    }

    const countResult = await productRepository.count({ where });
    const pagination = paginate(page, limit, countResult);

    const result = await productRepository.findAllAdmin({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { products: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async getProductById(id) {
    const product = await productRepository.findById(id);
    if (!product) throw new Error('Product not found');
    return product;
  }

  async createProduct(data) {
    const db = require('../../models');
    const transaction = await db.sequelize.transaction();
    
    try {
      data.slug = generateSlug(data.name);
      
      // Ensure unique slug
      const existing = await productRepository.count({ where: { slug: data.slug } });
      if (existing > 0) {
        data.slug = `${data.slug}-${Date.now()}`;
      }
      
      const product = await productRepository.create(data, transaction);
      
      const inventoryRepository = require('../inventory/inventory.repository');
      await inventoryRepository.create({
        product_id: product.id,
        quantity: parseInt(data.stock_quantity) || 0,
        low_stock_threshold: parseInt(data.low_stock_threshold) || 10,
      }, transaction);
      
      await transaction.commit();
      logger.info('Product created', { productId: product.id, name: product.name });
      return product;
    } catch (err) {
      await transaction.rollback();
      logger.error('Product creation failed', { error: err.message, name: data.name });
      throw err;
    }
  }

  async updateProduct(id, data) {
    if (data.name) {
      data.slug = generateSlug(data.name);
      const { Op } = require('sequelize');
      const existing = await productRepository.count({
        where: {
          slug: data.slug,
          id: { [Op.ne]: id }
        }
      });
      if (existing > 0) {
        data.slug = `${data.slug}-${Date.now()}`;
      }
    }

    const product = await productRepository.update(id, data);
    logger.info('Product updated', { productId: id });
    return product;
  }

  async deleteProduct(id) {
    await productRepository.delete(id);
    logger.info('Product deleted', { productId: id });
  }

  async getFeaturedProducts() {
    return productRepository.getFeatured(8);
  }

  async getCategories() {
    // Category doesn't have a separate module yet, using db directly for now or move to repository
    // Let's create a CategoryRepository if needed, but for now we can put it in ProductRepository
    return productRepository.findAllCategories();
  }
}

module.exports = new ProductService();
