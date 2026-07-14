// document.addEventListener('submit', function (e) {
//   const form = e.target;

//   // Respects onsubmit="return confirm(...)" — if that returned false,
//   // the event is already marked prevented by the time we get here.
//   if (e.defaultPrevented) return;
//   if (form.hasAttribute('data-no-loading')) return;
//   if (form.checkValidity && !form.checkValidity()) return; // let native validation messages show

//   const submitBtn = form.querySelector('button[type="submit"]');
//   if (!submitBtn || submitBtn.disabled) return;

//   const loadingText = submitBtn.getAttribute('data-loading-text') || 'Please wait...';

//   submitBtn.innerHTML =
//     `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
//   submitBtn.disabled = true;
//   submitBtn.classList.add('disabled');

//   // Prevent double-submits if a form has more than one submit button.
//   form.querySelectorAll('button[type="submit"]').forEach(btn => btn.disabled = true);
// });

// // For JS-triggered "redirect" actions that aren't a real form submit
// // (e.g. the bulk-export button on clients/index.blade.php).
// window.setButtonLoading = function (btn, text = 'Please wait...') {
//   btn.dataset.originalHtml = btn.innerHTML;
//   btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${text}`;
//   btn.disabled = true;
// };

(function () {
  // Build the overlay once, reuse it every time.
  const overlay = document.createElement('div');
  overlay.id = 'globalLoadingOverlay';
  overlay.innerHTML = `
    <div class="global-loading-box">
      <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
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

  // Expose in case you need to trigger/hide it manually (e.g. AJAX calls).
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

  // Safety net: if the user hits back/forward and lands on a cached page
  // mid-spinner, don't leave them stuck looking at an overlay forever.
  window.addEventListener('pageshow', function (event) {
    if (event.persisted) hideOverlay();
  });

  window.setButtonLoading = function (btn, text = 'Please wait...') {
    showOverlay(text);
    btn.disabled = true;
  };
})();