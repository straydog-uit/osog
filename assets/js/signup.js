document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.signup-form');
  const errUser = document.querySelector('.err-username');
  const errEmail = document.querySelector('.err-email');
  const errPass = document.querySelector('.err-password');

  if (!form) return;

  form.addEventListener('submit', e => {
    e.preventDefault();

    errUser.textContent = '';
    errEmail.textContent = '';
    errPass.textContent = '';

    const pwd = form.password.value;
    const pwd2 = form.password_confirm.value;

    if (pwd !== pwd2) {
      errPass.textContent = "Passwords do not match";
      return;
    }

    fetch('/api/signup.php', {
      method: 'POST',
      credentials: 'include',
      body: new FormData(form)
    })
      .then(r => r.json())
      .then(d => {
        if (d.ok) {
          location.href = '/login/';
        } else {
          if (d.field === 'username') errUser.textContent = d.error;
          if (d.field === 'email') errEmail.textContent = d.error;
          if (d.field === 'password') errPass.textContent = d.error;
        }
      });
  });

});