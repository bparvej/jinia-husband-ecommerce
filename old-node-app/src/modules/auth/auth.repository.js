const db = require('../../models');

class AuthRepository {
  async findUserByEmail(email) {
    return db.User.findOne({
      where: { email, is_active: true },
      include: [{ model: db.Role }],
    });
  }

  async findUserById(id) {
    return db.User.findByPk(id, {
      include: [{ model: db.Role }],
    });
  }

  async updateLastLogin(userId) {
    return db.User.update(
      { last_login: new Date() },
      { where: { id: userId } }
    );
  }

  async createUser(data) {
    return db.User.create(data);
  }
}

module.exports = new AuthRepository();
