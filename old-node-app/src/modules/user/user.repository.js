const db = require('../../models');
const { Op } = require('sequelize');

class UserRepository {
  async findAll({ offset = 0, limit = 12, where = {}, order = [['created_at', 'DESC']] } = {}) {
    return db.User.findAndCountAll({
      where,
      include: [{ model: db.Role, attributes: ['id', 'name'] }],
      order,
      offset,
      limit,
      distinct: true,
    });
  }

  async findById(id) {
    return db.User.findByPk(id, {
      include: [{ model: db.Role }],
    });
  }

  async findByEmail(email) {
    return db.User.findOne({
      where: { email },
      include: [{ model: db.Role }],
    });
  }

  async create(data) {
    return db.User.create(data);
  }

  async update(id, data) {
    const user = await db.User.findByPk(id);
    if (!user) throw new Error('User not found');
    return user.update(data);
  }

  async delete(id) {
    const user = await db.User.findByPk(id);
    if (!user) throw new Error('User not found');
    return user.destroy();
  }

  async count(options = {}) {
    return db.User.count(options);
  }

  async getRoles() {
    return db.Role.findAll({ order: [['id', 'ASC']] });
  }
}

module.exports = new UserRepository();
