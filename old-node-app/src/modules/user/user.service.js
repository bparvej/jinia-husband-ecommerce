const userRepository = require('./user.repository');
const { paginate } = require('../../utils/pagination');
const bcrypt = require('bcryptjs');
const logger = require('../../utils/logger');

class UserService {
  async getUsers(page = 1, limit = 12, filters = {}) {
    const where = {};
    if (filters.role_id) where.role_id = filters.role_id;
    if (filters.search) {
      const { Op } = require('sequelize');
      where[Op.or] = [
        { name: { [Op.iLike]: `%${filters.search}%` } },
        { email: { [Op.iLike]: `%${filters.search}%` } },
        { phone: { [Op.iLike]: `%${filters.search}%` } },
      ];
    }

    const total = await userRepository.count({ where });
    const pagination = paginate(page, limit, total);
    const result = await userRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { users: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async getUserById(id) {
    const user = await userRepository.findById(id);
    if (!user) throw new Error('User not found');
    return user;
  }

  async createUser(data) {
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 12);
    }
    const user = await userRepository.create(data);
    logger.info('User created', { userId: user.id, email: user.email });
    return user;
  }

  async updateUser(id, data) {
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 12);
    } else {
      delete data.password;
    }
    const user = await userRepository.update(id, data);
    logger.info('User updated', { userId: id });
    return user;
  }

  async deleteUser(id) {
    await userRepository.delete(id);
    logger.info('User deleted', { userId: id });
  }

  async getRoles() {
    return userRepository.getRoles();
  }
}

module.exports = new UserService();
