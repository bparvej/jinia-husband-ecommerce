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
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.innerHTML = html;
    
    // Add the toast to the container
    container.appendChild(toast);
    
    // Automatic removal after 3.5 seconds (matches CSS animation)
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => {
        toast.remove();
      }, 300);
    }, 3500);
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

  // ── Sidebar responsive behavior ──
  const layout = document.querySelector('.admin-layout');
  
  // Close sidebar on mobile when a link is clicked
  document.body.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
      const link = e.target.closest('.sidebar-link');
      if (link) {
        // Alpine will handle the variable change if we dispatch an event or just let it be.
        // Since we use Alpine, we should use Alpine's state.
        // But we can also just trigger a click on the overlay.
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) overlay.click();
      }
    }
  });

  // Re-initialize Lucide icons after HTMX swaps
  document.body.addEventListener('htmx:afterSwap', () => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
});


// Global Modal Functions
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

