(function () {
  const overlay = document.createElement('div');
  overlay.id = 'globalLoadingOverlay';
  overlay.innerHTML = `
    <div class="global-loading-box">
      <div class="brand-bars">
        <span class="bar bar-1"></span>
        <span class="bar bar-2"></span>
        <span class="bar bar-3"></span>
      </div>
      <div class="global-loading-text mt-3">Please wait...</div>
    </div>
  `;
  document.addEventListener('DOMContentLoaded', () => document.body.appendChild(overlay));

  function showOverlay(text) {
    overlay.querySelector('.global-loading-text').textContent = text || 'Please wait...';
    overlay.classList.add('show');
  }

  function hideOverlay() {
    overlay.classList.remove('show');
  }

  window.showGlobalLoading = showOverlay;
  window.hideGlobalLoading = hideOverlay;

  document.addEventListener('submit', function (e) {
    const form = e.target;

    if (e.defaultPrevented) return;
    if (form.hasAttribute('data-no-loading')) return;
    if (form.checkValidity && !form.checkValidity()) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const loadingText = submitBtn?.getAttribute('data-loading-text') || 'Please wait...';

    showOverlay(loadingText);

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add('disabled');
    }
    form.querySelectorAll('button[type="submit"]').forEach(btn => btn.disabled = true);
  });

  window.addEventListener('pageshow', function (event) {
    if (event.persisted) hideOverlay();
  });

  window.setButtonLoading = function (btn, text = 'Please wait...') {
    showOverlay(text);
    btn.disabled = true;
  };
})();