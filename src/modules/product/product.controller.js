const productService = require('./product.service');
const { getPageNumbers } = require('../../utils/pagination');

class ProductController {
  // ─── ADMIN: List Products ───
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search, category_id, status } = req.query;
      const { products, pagination } = await productService.getProductsAdmin(page, 12, { search, category_id, status });
      const categories = await productService.getCategories();

      const viewData = {
        layout: 'layouts/admin',
        title: 'Products — HomeI Admin',
        products,
        categories,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { search, category_id, status },
      };

      if (req.headers['hx-request']) {
        return res.render('admin/products/partials/product-table', viewData);
      }

      res.render('admin/products/index', viewData);
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(500).send(`<div class="toast toast-error">${err.message}</div>`);
      }
      throw err;
    }
  }

  // ─── ADMIN: Create Form ───
  async adminCreate(req, res) {
    const categories = await productService.getCategories();
    res.render('admin/products/create', {
      layout: 'layouts/admin',
      title: 'Add Product — HomeI Admin',
      categories,
      product: {},
      error: null,
    });
  }

  // ─── ADMIN: Store Product ───
  async adminStore(req, res) {
    try {
      const data = {
        name: req.body.name,
        description: req.body.description,
        short_description: req.body.short_description,
        price: parseFloat(req.body.price),
        compare_price: req.body.compare_price ? parseFloat(req.body.compare_price) : null,
        cost_price: req.body.cost_price ? parseFloat(req.body.cost_price) : null,
        sku: req.body.sku || null,
        category_id: req.body.category_id ? parseInt(req.body.category_id) : null,
        badge: req.body.badge || null,
        is_active: req.body.is_active === 'on' || req.body.is_active === 'true',
        is_featured: req.body.is_featured === 'on' || req.body.is_featured === 'true',
        stock_quantity: req.body.stock_quantity,
        low_stock_threshold: req.body.low_stock_threshold,
      };

      // Handle file upload
      if (req.file) {
        data.image = `/uploads/products/${req.file.filename}`;
      }

      await productService.createProduct(data);

      if (req.headers['hx-request']) {
        res.set('HX-Redirect', '/admin/products');
        return res.send('');
      }
      res.redirect('/admin/products');
    } catch (err) {
      const categories = await productService.getCategories();
      res.render('admin/products/create', {
        layout: 'layouts/admin',
        title: 'Add Product — HomeI Admin',
        categories,
        product: req.body,
        error: err.message,
      });
    }
  }

  // ─── ADMIN: Edit Form ───
  async adminEdit(req, res) {
    try {
      const product = await productService.getProductById(req.params.id);
      const categories = await productService.getCategories();
      res.render('admin/products/edit', {
        layout: 'layouts/admin',
        title: `Edit ${product.name} — HomeI Admin`,
        product,
        categories,
        error: null,
      });
    } catch (err) {
      res.redirect('/admin/products');
    }
  }

  // ─── ADMIN: Update Product ───
  async adminUpdate(req, res) {
    try {
      const data = {
        name: req.body.name,
        description: req.body.description,
        short_description: req.body.short_description,
        price: parseFloat(req.body.price),
        compare_price: req.body.compare_price ? parseFloat(req.body.compare_price) : null,
        cost_price: req.body.cost_price ? parseFloat(req.body.cost_price) : null,
        sku: req.body.sku || null,
        category_id: req.body.category_id ? parseInt(req.body.category_id) : null,
        badge: req.body.badge || null,
        is_active: req.body.is_active === 'on' || req.body.is_active === 'true',
        is_featured: req.body.is_featured === 'on' || req.body.is_featured === 'true',
      };

      if (req.file) {
        data.image = `/uploads/products/${req.file.filename}`;
      }

      await productService.updateProduct(req.params.id, data);

      if (req.headers['hx-request']) {
        res.set('HX-Redirect', '/admin/products');
        return res.send('');
      }
      res.redirect('/admin/products');
    } catch (err) {
      const product = await productService.getProductById(req.params.id);
      const categories = await productService.getCategories();
      res.render('admin/products/edit', {
        layout: 'layouts/admin',
        title: `Edit ${product.name} — HomeI Admin`,
        product: { ...product.toJSON(), ...req.body },
        categories,
        error: err.message,
      });
    }
  }

  // ─── ADMIN: Delete Product ───
  async adminDelete(req, res) {
    try {
      await productService.deleteProduct(req.params.id);
      if (req.headers['hx-request']) {
        return res.send('');
      }
      res.redirect('/admin/products');
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  }

  // ─── STOREFRONT: Products API ───
  async apiList(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search, category_id } = req.query;
      const { products, pagination } = await productService.getProducts(page, 12, { search, category_id });
      res.json({ products, pagination });
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  }
}

module.exports = new ProductController();
