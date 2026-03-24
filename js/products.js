function renderProducts(items) {
  const box = $('#productsContainer');
  box.empty();
  if (!items.length) {
    box.html('<p class="text-muted">No products found.</p>');
    return;
  }
  items.forEach((item) => {
    box.append(`
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm fade-up">
          <img src="${item.image || 'images/placeholder.jpg'}" class="card-img-top" alt="${item.title}">
          <div class="card-body">
            <small class="text-muted">${item.category || 'General'}</small>
            <h5 class="card-title mt-2">${item.title}</h5>
            <p class="card-text">${item.description.slice(0, 100)}...</p>
            <p class="price-tag">$${item.price} / PKR ${(item.price * 280).toFixed(0)}</p>
            <div class="d-flex gap-2">
              <a href="product-details.html?id=${item.id}" class="btn btn-outline-primary btn-sm">Details</a>
              <button class="btn btn-primary btn-sm" onclick='addToGuestCart({"id":${item.id},"title":"${item.title}","price":${item.price}})'>Add to Cart</button>
            </div>
          </div>
        </div>
      </div>
    `);
  });
}

function loadProducts() {
  const params = {
    search: $('#search').val() || '',
    category: $('#category').val() || '',
    min_price: $('#minPrice').val() || 0,
    max_price: $('#maxPrice').val() || 999999,
  };

  $.getJSON('includes/products.php', params, (res) => {
    if (res.success) renderProducts(res.products);
  });
}
