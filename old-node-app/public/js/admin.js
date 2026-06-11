/* ============================================
   HomeI Admin — Interactive JS
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {
  // ── HTMX event listeners ──
  document.body.addEventListener('htmx:afterRequest', (event) => {
    // Show toast for successful mutations
    if (event.detail.xhr.status >= 200 && event.detail.xhr.status < 300) {
      const method = event.detail.requestConfig.verb;
      if (method === 'delete' || method === 'put' || method === 'post') {
        // Check if response contains a toast
        const response = event.detail.xhr.responseText;
        if (response && response.includes('toast')) {
          showToast(response);
        }
      }
    }
  });

  // ── Toast notification ──
  function showToast(html) {
    const container = document.getElementById('toast-container');
    if (container) {
      container.innerHTML = html;
      setTimeout(() => { container.innerHTML = ''; }, 3000);
    }
  }

  // ── Confirm dialogs for delete actions ──
  document.body.addEventListener('htmx:confirm', (e) => {
    if (e.detail.question) {
      e.preventDefault();
      if (confirm(e.detail.question)) {
        e.detail.issueRequest();
      }
    }
  });

  // ── Handle HX-Redirect header ──
  document.body.addEventListener('htmx:beforeSwap', (event) => {
    const redirectUrl = event.detail.xhr.getResponseHeader('HX-Redirect');
    if (redirectUrl) {
      window.location.href = redirectUrl;
    }
  });

  // ── Sidebar responsive toggle ──
  const layout = document.querySelector('.admin-layout');
  if (layout && window.innerWidth <= 768) {
    layout.classList.add('sidebar-collapsed');
  }
});
