async function api(path, options = {}) {
  const res = await fetch(path, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });
  return res.json();
}

async function loadProducts() {
  const payload = await api('/products');
  const products = payload.data || [];

  const table = document.getElementById('productsTable');
  table.innerHTML = `
    <tr><th>ID</th><th>SKU</th><th>Name</th><th>Price</th><th>Stock</th></tr>
    ${products.map(p => `<tr><td>${p.id}</td><td>${p.sku}</td><td>${p.name}</td><td>₹${p.price}</td><td>${p.stock_quantity}</td></tr>`).join('')}
  `;

  const select = document.getElementById('adjustProduct');
  select.innerHTML = products.map(p => `<option value="${p.id}">${p.name} (${p.sku})</option>`).join('');
}

async function loadOrders() {
  const payload = await api('/orders');
  const orders = payload.data || [];

  const table = document.getElementById('ordersTable');
  table.innerHTML = `
    <tr><th>ID</th><th>Customer</th><th>Status</th><th>Total</th><th>Action</th></tr>
    ${orders.map(o => `
      <tr>
        <td>${o.id}</td>
        <td>${o.customer_name}</td>
        <td><span class="badge">${o.status}</span></td>
        <td>₹${o.total_amount}</td>
        <td>
          <select id="status-${o.id}">
            ${['pending', 'paid', 'packed', 'shipped', 'delivered', 'cancelled'].map(s => `<option ${s === o.status ? 'selected' : ''}>${s}</option>`).join('')}
          </select>
          <button onclick="updateOrderStatus(${o.id})">Update</button>
        </td>
      </tr>
    `).join('')}
  `;
}

window.updateOrderStatus = async function updateOrderStatus(id) {
  const status = document.getElementById(`status-${id}`).value;
  await api(`/orders/${id}/status`, {
    method: 'PATCH',
    body: JSON.stringify({ status }),
  });
  await Promise.all([loadOrders(), loadProducts()]);
};

document.getElementById('createProductBtn').addEventListener('click', async () => {
  const payload = {
    sku: document.getElementById('sku').value.trim(),
    name: document.getElementById('name').value.trim(),
    category: document.getElementById('category').value.trim(),
    price: Number(document.getElementById('price').value),
    stock_quantity: Number(document.getElementById('stock').value),
  };

  await api('/products', { method: 'POST', body: JSON.stringify(payload) });
  await loadProducts();
});

document.getElementById('adjustBtn').addEventListener('click', async () => {
  const product_id = Number(document.getElementById('adjustProduct').value);
  const quantity_delta = Number(document.getElementById('adjustDelta').value);
  const reason = document.getElementById('adjustReason').value.trim() || 'manual_adjustment';

  await api('/inventory/adjust', {
    method: 'POST',
    body: JSON.stringify({ product_id, quantity_delta, reason }),
  });

  await loadProducts();
});

Promise.all([loadProducts(), loadOrders()]);
