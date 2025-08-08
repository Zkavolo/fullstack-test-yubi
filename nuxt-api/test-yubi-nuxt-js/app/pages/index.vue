<template>
  <div class="sales-order-page">
    <h1>Sales Orders</h1>

    <table class="sales-order-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>SO Number</th>
          <th>Customer</th>
          <th>SO Date</th>
          <th>Grand Total</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in salesOrders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ order.so_number }}</td>
          <td>{{ getCustomerName(order.customer_id) }}</td>
          <td>{{ order.so_date }}</td>
          <td>{{ formatNumber(order.grand_total) }}</td>
          <td class="space-x-2">
            <button class="btn btn-primary">
            <NuxtLink :to="`/salesOrder/${order.id}`"
              class="btn btn-primary">
              View
            </NuxtLink>
            </button>
            <button class="btn btn-danger" @click="deleteOrder(order.id)">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <button class="btn btn-primary mb-4" @click="showForm = !showForm">
      {{ showForm ? 'Cancel' : 'Create Sales Order' }}
    </button>

    <form v-if="showForm" @submit.prevent="submitForm" class="space-y-4 bg-white p-6 rounded shadow">
      <div>
        <label>SO Number</label>
        <input v-model="form.so_number" class="input" required />
      </div>

      <div>
        <label>SO Date</label>
        <input type="date" v-model="form.so_date" class="input" required />
      </div>

      <div>
        <label>Ship Date</label>
        <input type="date" v-model="form.ship_date" class="input" required />
      </div>

      <div>
        <label>Ship Destination</label>
        <input v-model="form.ship_dest" class="input" required />
      </div>

      <div>
        <h3>Products</h3>
        <div v-for="(item, index) in form.soDts" :key="index" class="p-4 border rounded space-y-2">
          <div>
            <label>Product UUID</label>
            <input v-model="item.product_uuid" class="input" required />
          </div>

          <div>
            <label>Quantity</label>
            <input type="number" v-model.number="item.quantity" class="input" min="1" @input="updateTotal(index)" />
          </div>

          <div>
            <label>Price</label>
            <input type="number" v-model.number="item.price" class="input" min="0" @input="updateTotal(index)" />
          </div>

          <div>
            <label>Discount (%)</label>
            <input type="number" v-model.number="item.disc_perc" class="input" min="0" max="100" @input="updateTotal(index)" />
          </div>

          <div>
            <label>Total</label>
            <input :value="item.total_am" class="input bg-gray-100" disabled />
          </div>

          <button type="button" class="btn btn-danger" @click="removeItem(index)">
            Remove Product
          </button>
        </div>

        <button type="button" class="btn btn-secondary mt-2" @click="addItem">
          + Add Product
        </button>
      </div>

      <button type="submit" class="btn btn-success">Submit</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { customers } from '../data/data.js'
import axios from 'axios'

const salesOrders = ref([])
const showForm = ref(false)
const form = ref({
  so_number: 'SO-2025-0001',
  so_date: '2025-04-17',
  ship_date: '2025-04-24',
  customer_id: 1,
  currency_id: 1,
  order_type: 1,
  status: 'open',
  vat_id: 0,
  pph23_id: 0,
  ship_dest: 'Buyer 1 Address',
  discount_type: null,
  soDts: [
    {
      product_uuid: 'uuid-pc-server-a',
      ref_type: 'Products',
      quantity: 1,
      price: 10000000,
      disc_perc: 0,
      disc_am: 0,
      total_am: 10000000,
      remark: '',
    },
    {
      product_uuid: 'uuid-psu-500w',
      ref_type: 'Products',
      quantity: 4,
      price: 350000,
      disc_perc: 0,
      disc_am: 0,
      total_am: 1400000,
      remark: '',
    },
  ],
})

const fetchSalesOrders = async () => {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/sales-orders')
    const data = await res.json()
    salesOrders.value = data
  } catch (err) {
    console.error('Error fetching sales orders:', err)
  }
}

onMounted(fetchSalesOrders)

function getCustomerName(id) {
  const customer = customers.find(c => c.id === id)
  return customer ? customer.name : 'Unknown'
}

function formatNumber(value) {
  return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(value || 0)
}

// penjelasan sedikit karena sbenarnya diskon sudah dihandle di backend tapi hal ini dikarenakan untuk memperlihatkan live view untuk nilai disc_am
function updateTotal(index) {
  const item = form.value.soDts[index]
  const discount = item.price * item.quantity * (item.disc_perc / 100)
  item.disc_am = discount
  item.total_am = item.price * item.quantity - discount
}

function addItem() {
  form.value.soDts.push({
    product_uuid: '',
    ref_type: 'Products',
    quantity: 1,
    price: 0,
    disc_perc: 0,
    disc_am: 0,
    total_am: 0,
    remark: '',
  })
}

function removeItem(index) {
  form.value.soDts.splice(index, 1)
}

const submitForm = async () => {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/sales-orders', form.value)
    alert('Sales order created successfully!')
    console.log(response.data)
    showForm.value = false
    await fetchSalesOrders()
  } catch (error) {
    console.error('Error submitting sales order:', error)
    alert('Failed to create sales order.')
  }
}

const deleteOrder = async (id) => {
  if (!confirm('Are you sure you want to delete this sales order?')) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/sales-orders/${id}`)
    salesOrders.value = salesOrders.value.filter(order => order.id !== id)
    alert('Sales order deleted successfully!')
    await fetchSalesOrders()
  } catch (error) {
    console.error('Failed to delete sales order:', error)
    alert('Failed to delete sales order.')
  }
}

</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 0.25rem;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-success {
  background: #10b981;
  color: white;
}

.btn-danger {
  background: #ef4444;
  color: white;
}

.sales-order-page {
  padding: 1rem;
}

.sales-order-table {
  width: 100%;
  border-collapse: collapse;
}

.sales-order-table th,
.sales-order-table td {
  border: 1px solid #ccc;
  padding: 0.5rem;
}

.sales-order-table th {
  background-color: #eee;
}
</style>
