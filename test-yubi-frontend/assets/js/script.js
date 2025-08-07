function loadSalesOrder(soId) {
  fetch(`http://localhost:8000/api/sales-orders/${soId}`)
    .then(response => response.json())
    .then(result => {
        console.log(result);
      if (result) {
        const data = result;

        // Ambil data customer dari data.js
        const customer = customers.find(c => c.id === data.customer_id) || {};
        const currency = currencies.find(c => c.code === String(data.currency_id)) || {};
        const orderType = orderTypes.find(o => o.value === data.order_type) || {};

        document.getElementById('po_number').value = data.so_number;
        document.getElementById('order_type').value = orderType.label || data.order_type;
        document.getElementById('so_date').value = data.so_date;
        document.getElementById('ship_date').value = data.ship_date;
        document.getElementById('customer').value = customer.name || '';
        document.getElementById('email').value = customer.email || '';
        document.getElementById('phone').value = customer.phone || '';
        document.getElementById('currency').value = currency.name || '';
        document.getElementById('exchange_rate').value = formatNumber(data.exchange_rate || 1);
        document.getElementById('vatToggle').checked = data.vat_id === 1;
        document.getElementById('pph').value = data.pph23_id || '';
        document.getElementById('sub_amount').textContent = formatNumber(data.sa_total_am);
        document.getElementById('grand_total').textContent = formatNumber(data.grand_total);

        const tbody = document.getElementById('items-body');
        tbody.innerHTML = '';

        data.so_dts.forEach(item => {
          const product = products.find(p => p.uuid === item.product_uuid) || {};

          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${item.ref_type || 'N/A'}</td>
            <td>${product.ref_number || ''}</td>
            <td>${item.item_type || 'Products'}</td>
            <td>${product.uuid || item.product_uuid}</td>
            <td>${product.name || ''}</td>
            <td>${product.unit || ''}</td>
            <td>${formatNumber(item.price)}</td>
            <td>${formatNumber(item.quantity)}</td>
            <td>${formatNumber(item.disc_perc)}</td>
            <td>${formatNumber(item.disc_am)}</td>
            <td>${formatNumber(item.total_am)}</td>
            <td><input class="form-control form-control-sm" placeholder="Remark" value="${item.remark || ''}"></td>
            <td><button class="btn btn-sm btn-outline-danger"><i data-feather="trash-2"></i></button></td>
          `;
          tbody.appendChild(row);
        });

        feather.replace(); // Render ulang feather icons
      }
    });
}

function formatNumber(value) {
  return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(value || 0);
}

document.addEventListener('DOMContentLoaded', function () {
  const soId = 5; // Ganti sesuai kebutuhan
  loadSalesOrder(soId);
});
