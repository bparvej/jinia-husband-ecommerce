/**
 * Build pagination metadata for list queries
 * @param {number} page - Current page
 * @param {number} limit - Items per page
 * @param {number} totalItems - Total records count
 * @returns {{ offset, limit, page, totalPages, totalItems, hasNext, hasPrev }}
 */
function paginate(page = 1, limit = 12, totalItems = 0) {
  const currentPage = Math.max(1, parseInt(page, 10) || 1);
  const perPage = Math.min(100, Math.max(1, parseInt(limit, 10) || 12));
  const totalPages = Math.ceil(totalItems / perPage) || 1;
  const offset = (currentPage - 1) * perPage;

  return {
    offset,
    limit: perPage,
    page: currentPage,
    totalPages,
    totalItems,
    hasNext: currentPage < totalPages,
    hasPrev: currentPage > 1,
  };
}

/**
 * Generate page numbers array for pagination UI
 */
function getPageNumbers(currentPage, totalPages, maxVisible = 5) {
  const pages = [];
  let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let end = Math.min(totalPages, start + maxVisible - 1);
  start = Math.max(1, end - maxVisible + 1);

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
}

module.exports = { paginate, getPageNumbers };
