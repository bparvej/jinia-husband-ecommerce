const couponRepository = require('./coupon.repository');
const { paginate } = require('../../utils/pagination');

class CouponService {
  async getCoupons(page = 1, limit = 12, filters = {}) {
    const where = {};
    if (filters.search) {
      const { Op } = require('sequelize');
      where.code = { [Op.iLike]: `%${filters.search}%` };
    }

    const total = await couponRepository.count({ where });
    const pagination = paginate(page, limit, total);
    const result = await couponRepository.findAll({
      offset: pagination.offset,
      limit: pagination.limit,
      where,
    });

    return { coupons: result.rows, pagination: { ...pagination, totalItems: result.count } };
  }

  async validateCoupon(code, cartTotal) {
    const coupon = await couponRepository.findByCode(code);
    if (!coupon) throw new Error('Invalid or expired coupon');

    const now = new Date();
    if (coupon.start_date && now < coupon.start_date) throw new Error('Coupon not yet active');
    if (coupon.end_date && now > coupon.end_date) throw new Error('Coupon expired');
    if (coupon.min_order_amount && cartTotal < coupon.min_order_amount) {
      throw new Error(`Minimum order amount of ${coupon.min_order_amount} required`);
    }
    if (coupon.usage_limit && coupon.used_count >= coupon.usage_limit) {
      throw new Error('Coupon usage limit reached');
    }

    let discount = 0;
    if (coupon.discount_type === 'percentage') {
      discount = (cartTotal * coupon.discount_value) / 100;
      if (coupon.max_discount_amount) {
        discount = Math.min(discount, coupon.max_discount_amount);
      }
    } else {
      discount = coupon.discount_value;
    }

    return { coupon, discount };
  }

  async createCoupon(data) {
    return couponRepository.create(data);
  }

  async updateCoupon(id, data) {
    return couponRepository.update(id, data);
  }

  async deleteCoupon(id) {
    return couponRepository.delete(id);
  }

  async getCouponById(id) {
    return couponRepository.findById(id);
  }
}

module.exports = new CouponService();
