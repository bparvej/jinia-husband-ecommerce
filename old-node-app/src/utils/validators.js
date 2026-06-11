/**
 * Input validation helpers
 */

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function validateRequired(fields, body) {
  const missing = [];
  for (const field of fields) {
    if (!body[field] || (typeof body[field] === 'string' && body[field].trim() === '')) {
      missing.push(field);
    }
  }
  return missing;
}

function sanitizeString(str) {
  if (typeof str !== 'string') return str;
  return str.replace(/[<>]/g, '').trim();
}

function generateSlug(name) {
  return name
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_]+/g, '-')
    .replace(/^-+|-+$/g, '')
    .substring(0, 120);
}

function generateOrderNumber() {
  const prefix = 'HI';
  const year = new Date().getFullYear();
  const random = Math.floor(Math.random() * 9000) + 1000;
  const timestamp = Date.now().toString().slice(-4);
  return `${prefix}-${year}${timestamp}${random}`;
}

function formatCurrency(amount) {
  return `৳${Number(amount).toLocaleString('en-BD')}`;
}

module.exports = {
  validateEmail,
  validateRequired,
  sanitizeString,
  generateSlug,
  generateOrderNumber,
  formatCurrency,
};
