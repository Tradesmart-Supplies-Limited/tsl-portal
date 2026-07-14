document.addEventListener('submit', function (e) {
  const form = e.target;

  // Respects onsubmit="return confirm(...)" — if that returned false,
  // the event is already marked prevented by the time we get here.
  if (e.defaultPrevented) return;
  if (form.hasAttribute('data-no-loading')) return;
  if (form.checkValidity && !form.checkValidity()) return; // let native validation messages show

  const submitBtn = form.querySelector('button[type="submit"]');
  if (!submitBtn || submitBtn.disabled) return;

  const loadingText = submitBtn.getAttribute('data-loading-text') || 'Please wait...';

  submitBtn.innerHTML =
    `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
  submitBtn.disabled = true;
  submitBtn.classList.add('disabled');

  // Prevent double-submits if a form has more than one submit button.
  form.querySelectorAll('button[type="submit"]').forEach(btn => btn.disabled = true);
});

// For JS-triggered "redirect" actions that aren't a real form submit
// (e.g. the bulk-export button on clients/index.blade.php).
window.setButtonLoading = function (btn, text = 'Please wait...') {
  btn.dataset.originalHtml = btn.innerHTML;
  btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${text}`;
  btn.disabled = true;
};