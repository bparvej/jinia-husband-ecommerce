const db = require('../../models');

class CouponRepository {
  async findAll({ offset = 0, limit = 12, where = {} } = {}) {
    return db.Coupon.findAndCountAll({
      where,
      order: [['created_at', 'DESC']],
      offset,
      limit,
    });
  }

  async findByCode(code) {
    return db.Coupon.findOne({ where: { code, is_active: true } });
  }

  async create(data) {
    return db.Coupon.create(data);
  }

  async update(id, data) {
    const coupon = await db.Coupon.findByPk(id);
    if (!coupon) throw new Error('Coupon not found');
    return coupon.update(data);
  }

  async delete(id) {
    const coupon = await db.Coupon.findByPk(id);
    if (!coupon) throw new Error('Coupon not found');
    return coupon.destroy();
  }

  async count(where = {}) {
    return db.Coupon.count({ where });
  }

  async findById(id) {
    return db.Coupon.findByPk(id);
  }
}

module.exports = new CouponRepository();
