const form = document.querySelector('.login-form');
const err  = document.querySelector('.error');

form.addEventListener('submit', e => {
  e.preventDefault();
  err.textContent = '';

  fetch('/api/login.php', {
    method: 'POST',
    credentials: 'include',
    body: new FormData(form)
  })
    .then(r => r.json())
    .then(d => {
      if (d.ok) {
        location.href = '/';
      } else {
        err.textContent = 'Incorrect username or password';
      }
    })
    .catch(() => {
      err.textContent = 'Network error';
    });
});