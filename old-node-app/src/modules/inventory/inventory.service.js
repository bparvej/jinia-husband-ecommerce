const inventoryRepository = require('./inventory.repository');
const { paginate } = require('../../utils/pagination');
const db = require('../../models');
const logger = require('../../utils/logger');

class InventoryService {
  async getInventory(page = 1, limit = 20, filters = {}) {
    const where = {};
    if (filters.search) {
      const { Op } = require('sequelize');
      where[Op.or] = [
        { name: { [Op.iLike]: `%${filters.search}%` } },
        { sku: { [Op.iLike]: `%${filters.search}%` } },
      ];
    }

    const total = await inventoryRepository.count();
    const pagination = paginate(page, limit, total);
    const result = await inventoryRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return {
      inventory: result.rows,
      pagination: { ...pagination, totalItems: result.count },
    };
  }

  async updateStock(productId, quantity) {
    await inventoryRepository.updateStock(productId, parseInt(quantity));
    logger.info('Stock updated', { productId, newQuantity: quantity });
  }

  async getLowStockAlerts() {
    return inventoryRepository.getLowStock();
  }
}

module.exports = new InventoryService();
