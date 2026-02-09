const productsEl = document.getElementById('products');
const cartItemsEl = document.getElementById('cartItems');
const cartTotalEl = document.getElementById('cartTotal');
const messageEl = document.getElementById('message');

const cart = new Map();

async function api(path, options = {}) {
  const res = await fetch(path, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });
  return res.json();
}

function renderCart() {
  const rows = [...cart.values()];
  if (rows.length === 0) {
    cartItemsEl.innerHTML = '<p class="muted">Your cart is empty.</p>';
    cartTotalEl.textContent = '0';
    return;
  }

  let total = 0;
  cartItemsEl.innerHTML = rows.map(item => {
    total += item.quantity * item.price;
    return `<div>${item.name} x ${item.quantity} = ₹${item.quantity * item.price}
      <button class="secondary" onclick="removeFromCart(${item.id})">Remove</button></div>`;
  }).join('');

  cartTotalEl.textContent = String(total);
}

window.removeFromCart = function removeFromCart(id) {
  cart.delete(id);
  renderCart();
};

window.addToCart = function addToCart(id, name, price) {
  const existing = cart.get(id);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.set(id, { id, name, price, quantity: 1 });
  }
  renderCart();
};

async function loadProducts() {
  const payload = await api('/products');
  const products = payload.data || [];

  productsEl.innerHTML = products.map(product => `
    <article class="product">
      <h3>${product.name}</h3>
      <p class="muted">SKU: ${product.sku}</p>
      <p>Category: ${product.category ?? '-'}</p>
      <p><strong>₹${product.price}</strong></p>
      <p>Stock: ${product.stock_quantity}</p>
      <button onclick="addToCart(${product.id}, '${String(product.name).replace(/'/g, "\\'")}', ${product.price})" ${product.stock_quantity <= 0 ? 'disabled' : ''}>Add to Cart</button>
    </article>
  `).join('');
}

document.getElementById('placeOrderBtn').addEventListener('click', async () => {
  const customer_name = document.getElementById('customerName').value.trim();
  const customer_phone = document.getElementById('customerPhone').value.trim();
  const customer_address = document.getElementById('customerAddress').value.trim();
  const items = [...cart.values()].map(x => ({
    product_id: x.id,
    quantity: x.quantity,
    unit_price: x.price,
  }));

  if (!customer_name || items.length === 0) {
    messageEl.textContent = 'Please add customer name and at least one product.';
    return;
  }

  const payload = await api('/orders', {
    method: 'POST',
    body: JSON.stringify({ customer_name, customer_phone, customer_address, items }),
  });

  if (payload.success) {
    messageEl.textContent = `Order #${payload.data.id} placed successfully.`;
    cart.clear();
    renderCart();
    await loadProducts();
  } else {
    messageEl.textContent = payload.error?.message || 'Order failed.';
  }
});

loadProducts();
renderCart();
