const box = document.getElementById('bookmark-list');

async function loadBookmarks() {
  try {
    const res = await fetch('/api/bookmark_list.php', {credentials: 'include', cache: 'no-store'});
    const data = await res.json();
    box.innerHTML = '';

    if (!data.ok || data.bookmarks.length === 0) {
      box.innerHTML = '<p>No bookmarks yet.</p>';
      return;
    }

    const ul = document.createElement('ul');
    ul.style.listStyleType = 'square';

    data.bookmarks.forEach(item => {
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = item.url;
      a.textContent = item.title || item.url;
      li.appendChild(a);
      ul.appendChild(li);
    });
    box.appendChild(ul);
  } catch (err) {
    box.innerHTML = '<p>Error loading bookmarks.</p>';
    console.error(err);
  }
}

fetch('/api/auth_status.php', {credentials: 'include', cache: 'no-store'})
  .then(r => r.json())
  .then(auth => {
    if (!auth.logged_in) {
      location.href = '/login/';
      return;
    }
    loadBookmarks();
  });