require('dotenv').config();

const express = require('express');
const path = require('path');
const helmet = require('helmet');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const methodOverride = require('method-override');
const expressLayouts = require('express-ejs-layouts');

const sessionConfig = require('./config/session');
const { loadUser } = require('./middleware/auth');
const { csrfTokenGenerator } = require('./middleware/csrf');
const { apiLimiter } = require('./middleware/rateLimiter');
const { errorHandler, notFoundHandler } = require('./middleware/errorHandler');
const logger = require('./utils/logger');
const db = require('./models');

const app = express();

// ─── Security ───
app.use(helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      styleSrc: ["'self'", "'unsafe-inline'", "https://fonts.googleapis.com"],
      fontSrc: ["'self'", "https://fonts.gstatic.com"],
      scriptSrc: ["'self'", "'unsafe-inline'", "'unsafe-eval'", "https://unpkg.com"],
      scriptSrcAttr: ["'unsafe-inline'"],
      imgSrc: ["'self'", "data:", "blob:", "https://*"],
      connectSrc: ["'self'"],
    },
  },
  crossOriginEmbedderPolicy: false,
}));

// ─── Body Parsing ───
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));
app.use(cookieParser());
app.use(methodOverride('_method'));

// ─── Sessions ───
app.use(session(sessionConfig));

// ─── View Engine (EJS) ───
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, '../views'));
app.use(expressLayouts);
app.set('layout', 'layouts/main');
// Extraction disabled to prevent styles being lost in certain layout configurations

// ─── Static Files ───
app.use(express.static(path.join(__dirname, '../public')));

// ─── Load User & CSRF ───
app.use(loadUser(db));
app.use(csrfTokenGenerator);

// ─── Global template variables ───
app.use((req, res, next) => {
  res.locals.path = req.path;
  res.locals.query = req.query;
  next();
});

// ─── Rate Limiting ───
app.use('/api/', apiLimiter);

// ─── Routes ───
const authRoutes = require('./modules/auth/auth.routes');
const userRoutes = require('./modules/user/user.routes');
const productRoutes = require('./modules/product/product.routes');
const inventoryRoutes = require('./modules/inventory/inventory.routes');
const orderRoutes = require('./modules/order/order.routes');
const cartRoutes = require('./modules/cart/cart.routes');
const paymentRoutes = require('./modules/payment/payment.routes');
const couponRoutes = require('./modules/coupon/coupon.routes');
const reviewRoutes = require('./modules/review/review.routes');
const shippingRoutes = require('./modules/shipping/shipping.routes');
const notificationRoutes = require('./modules/notification/notification.routes');
const reportRoutes = require('./modules/report/report.routes');

app.use(authRoutes);
app.use(userRoutes);
app.use(productRoutes);
app.use(inventoryRoutes);
app.use(orderRoutes);
app.use(cartRoutes);
app.use(paymentRoutes);
app.use(couponRoutes);
app.use(reviewRoutes);
app.use(shippingRoutes);
app.use(notificationRoutes);
app.use(reportRoutes);

// ─── Storefront Home ───
const productService = require('./modules/product/product.service');

app.get('/', async (req, res) => {
  try {
    const { products } = await productService.getProducts(1, 8);
    const categories = await productService.getCategories();
    const featuredProducts = await productService.getFeaturedProducts();

    res.render('pages/home', {
      layout: 'layouts/main',
      title: 'HomeI — Cozy Living | Wooden & Home Decor Furniture',
      products,
      categories,
      featuredProducts,
    });
  } catch (err) {
    logger.error('Homepage error', { error: err.message });
    res.render('pages/home', {
      layout: 'layouts/main',
      title: 'HomeI — Cozy Living',
      products: [],
      categories: [],
      featuredProducts: [],
    });
  }
});

// ─── Error Handling ───
app.use(notFoundHandler);
app.use(errorHandler);

module.exports = app;
