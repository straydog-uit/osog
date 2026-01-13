const scriptTag = document.querySelector(
  'script[type="module"][src$="auth.js"]'
);
const classTarget = scriptTag?.dataset.classTarget;

const target = document.querySelector(classTarget);
if (target) target.hidden = true;

fetch('/api/auth_status.php', {credentials: 'include', cache: 'no-store'})
  .then(res => res.json())
  .then(auth => {
    if (!auth.logged_in) {
      location.href = '/login/';
    } else if (target) {
      target.hidden = false;
    }
  });