const form = document.querySelector('.update-password-form');
const errorBox = form.querySelector('.error');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  errorBox.textContent = '';

  const fd = new FormData(form);

  const newPass = fd.get('new_password');
  const confirm = fd.get('password_confirm');

  if (newPass !== confirm) {
    errorBox.textContent = 'Passwords do not match';
    return;
  }

  const send = new FormData();
  send.append('old_password', fd.get('old_password'));
  send.append('new_password', newPass);

  const res = await fetch('/api/update_password.php', {
    method: 'POST',
    body: send,
    credentials: 'include'
  });

  const json = await res.json();

  if (json.ok) {
    form.reset();
    alert('Password updated successfully.');
    location.href = '/settings/';
  } else {
    errorBox.textContent = json.error || 'Error';
  }
});