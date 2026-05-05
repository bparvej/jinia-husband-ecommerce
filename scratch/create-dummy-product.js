const path = require('path');
const db = require(path.join(__dirname, '../src/models'));
const productService = require(path.join(__dirname, '../src/modules/product/product.service'));

async function createDummyProduct() {
  try {
    const categories = await db.Category.findAll();
    if (categories.length === 0) {
      console.log('No categories found. Creating one...');
      await db.Category.create({
        name: 'Test Category',
        slug: 'test-category',
        is_active: true
      });
    }
    
    const cat = await db.Category.findOne();
    
    const dummyData = {
      name: 'Modern Oak Coffee Table (Test)',
      description: 'A beautifully designed modern coffee table made from solid oak. Perfect for any living room.',
      short_description: 'Solid oak coffee table with a modern finish.',
      price: 12500,
      compare_price: 15000,
      category_id: cat.id,
      is_active: true,
      is_featured: true,
      stock_quantity: 25,
      low_stock_threshold: 5,
      badge: 'New'
    };
    
    const product = await productService.createProduct(dummyData);
    console.log('Dummy product created successfully with ID:', product.id);
    process.exit(0);
  } catch (err) {
    console.error('Error creating dummy product:', err);
    process.exit(1);
  }
}

createDummyProduct();
