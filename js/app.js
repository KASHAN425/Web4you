const isDark = localStorage.getItem('theme') === 'dark';
if (isDark) document.body.classList.add('dark-mode');

function toggleTheme() {
  document.body.classList.toggle('dark-mode');
  localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
}

function addToGuestCart(product) {
  const cart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
  const found = cart.find((item) => item.id === product.id);
  if (found) found.quantity += 1;
  else cart.push({ ...product, quantity: 1 });
  localStorage.setItem('guest_cart', JSON.stringify(cart));
  alert('Added to cart');
}

function logout() {
  $.post('includes/auth.php', { action: 'logout' }, () => {
    window.location.href = 'login.html';
  });
}
