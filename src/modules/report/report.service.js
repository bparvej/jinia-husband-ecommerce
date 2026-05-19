const db = require('../../models');
const reportRepository = require('./report.repository');
const orderRepository = require('../order/order.repository');
const productRepository = require('../product/product.repository');
const userRepository = require('../user/user.repository');

class ReportService {
  async getDashboardStats() {
    const orderStats = await orderRepository.getOrderStats();
    const totalProducts = await productRepository.count({ where: { is_active: true } });
    const totalCustomers = await userRepository.count({ where: { role_id: 4 } }); // Assuming 4 is customer role
    
    // Revenue last 7 days
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 7);
    
    const salesData = await reportRepository.getSalesReport(startDate, endDate);

    return {
      totalOrders: orderStats.total_orders,
      pendingOrders: orderStats.pending_orders,
      totalRevenue: orderStats.total_revenue,
      totalProducts,
      totalCustomers,
      revenueChart: salesData,
    };
  }

  async getSalesReport(range = '30days') {
    const endDate = new Date();
    const startDate = new Date();
    
    if (range === '7days') startDate.setDate(startDate.getDate() - 7);
    else if (range === '12months') startDate.setMonth(startDate.getMonth() - 12);
    else startDate.setDate(startDate.getDate() - 30);

    const sales = await reportRepository.getSalesReport(startDate, endDate);
    const topProducts = await reportRepository.getTopProducts(startDate, endDate);
    const categorySales = await reportRepository.getCategorySales(startDate, endDate);
    const paymentSales = await reportRepository.getPaymentMethodSales(startDate, endDate);

    return { sales, topProducts, categorySales, paymentSales };
  }
}

module.exports = new ReportService();
