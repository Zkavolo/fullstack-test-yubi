<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar">
      <div class="navbar-left">
        <AlignJustify />
        <div class="navbar-info">
          <span class="title">Sales Orders</span>
          <div class="breadcrumbs">
            <span>Sales</span>
            <span>&gt;</span>
            <span>Sales Orders</span>
            <span>&gt;</span>
            <span>Edit</span>
            <span>&gt;</span>
            <span class="current">{{ orderId }}</span>
          </div>
        </div>
      </div>
      <div class="navbar-right">
        <button class="icon-btn"><SunSnow /></button>
        <button class="icon-btn"><LogOut /></button>
      </div>
    </nav>

    <!-- Form -->
    <div class="form-container" v-if="salesOrder">
      <div class="form-header">
        <div class="form-title">
          <strong>Basic Information</strong>
          <span class="form-icon"><EyeOff /></span>
          <NuxtLink to="/">
            <button class="goto-link">
              <Search />Go To Sales Order
            </button>
          </NuxtLink>
        </div>
        <div class="form-actions">
          <button class="btn create">Create New</button>
          <button class="btn update">Update</button>
          <button class="btn clear">Clear</button>
          <button class="btn danger">
            <NuxtLink to="/">Go To List</NuxtLink>
          </button>
        </div>
      </div>

      <div class="form-grid">
        <input type="text" placeholder="PO Buyer No" :value="salesOrder.so_number" disabled />
        <select>
          <option :selected="true">{{ orderType?.label || salesOrder.order_type }}</option>
        </select>
        <div class="customer-select">
          <span>Customer: {{ customer.name }}</span>
          <button class="x-btn">×</button>
        </div>
        <input type="text" placeholder="Email" :value="customer.email" disabled />
        <input type="text" placeholder="Phone" :value="customer.phone" disabled />
        <select>
          <option :selected="true">PROCESS</option>
        </select>
        <input type="date" :value="salesOrder.so_date" />
        <input type="date" :value="salesOrder.ship_date" />
        <select>
          <option :selected="true">{{ currency.name }}</option>
        </select>
        <input type="number" :value="formattedExchangeRate" placeholder="Exchange Rate" />
        <select>
          <option :selected="true">{{ salesOrder.pph23_id }}</option>
        </select>
        <label class="vat-toggle">
          VAT
          <input type="checkbox" :checked="salesOrder.vat_id === 1" />
          <span class="slider"></span>
        </label>
      </div>

      <textarea class="address-input">{{ salesOrder.ship_dest }}</textarea>

      <div class="form-summary">
        <div class="summary-row">
          <span class="summary-label">Sub Amount</span>
          <span class="summary-value">Rp. {{ formatNumber(salesOrder.sa_total_am) }}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Total Discount</span>
          <span class="summary-value">Rp. 0.00</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">After Discount</span>
          <span class="summary-value">Rp. {{ formatNumber(salesOrder.sa_total_am) }}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Total VAT</span>
          <span class="summary-value">Rp. 0.00</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Total PPH23</span>
          <span class="summary-value">Rp. 0.00</span>
        </div>
        <div class="summary-row">
          <span class="summary-label">Grand Total</span>
          <span class="summary-value">Rp. {{ formatNumber(salesOrder.grand_total) }}</span>
        </div>
      </div>
    </div>

    <!-- Sales Order Items Table -->
    <div class="product-table-wrapper">
      <div class="tab-header">
        <button class="tab active">Ms. Product ({{ salesOrder?.so_dts?.length || 0 }})</button>
        <button class="tab">Quotation (0)</button>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Ref Type</th>
              <th>Ref Num</th>
              <th>Item Type</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Unit</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Disc (%)</th>
              <th>Disc Amount</th>
              <th>Total Amount</th>
              <th>Remark</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in salesOrder?.so_dts || []" :key="index">
              <td>Products</td>
              <td>{{ getProduct(item.product_uuid)?.ref_number || '' }}</td>
              <td>{{ item.item_type || 'Product' }}</td>
              <td>{{ item.product_uuid }}</td>
              <td>{{ getProduct(item.product_uuid)?.name || '' }}</td>
              <td>{{ getProduct(item.product_uuid)?.unit || '' }}</td>
              <td>{{ formatNumber(item.price) }}</td>
              <td>{{ formatNumber(item.quantity) }}</td>
              <td>{{ formatNumber(item.disc_perc) }}</td>
              <td>{{ formatNumber(item.disc_am) }}</td>
              <td>{{ formatNumber(item.total_am) }}</td>
              <td><input type="text" :value="item.remark || ''" placeholder="Remark" /></td>
              <td>
                <button class="btn-update">Update</button>
                <button class="btn-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { AlignJustify, EyeOff, LogOut, Search, SunSnow } from 'lucide-vue-next'
import { NuxtLink } from '#components'
import { customers, currencies, statusTypes, orderTypes, products } from '../../data/data.js'

const route = useRoute()
const orderId = route.params.id
const salesOrder = ref(null)

const customer = computed(() => {
  return customers.find(c => c.id === salesOrder.value?.customer_id) || {}
})

const currency = computed(() => {
  return currencies.find(c => c.code === String(salesOrder.value?.currency_id)) || {}
})

const orderType = computed(() => {
  return orderTypes.find(o => o.value === salesOrder.value?.order_type) || {}
})

const formattedExchangeRate = computed(() => formatNumber(salesOrder.value?.exchange_rate || 1))

function getProduct(uuid) {
  return products.find(p => p.uuid === uuid)
}

function formatNumber(value) {
  return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(value || 0)
}

async function loadSalesOrder(id) {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/sales-orders/${id}`)
    const data = await response.json()
    console.log('Sales Order Data:', data)
    salesOrder.value = data
  } catch (error) {
    console.error('Failed to load sales order:', error)
  }
}

onMounted(() => {
  loadSalesOrder(orderId)
})
</script>

<style scoped>
a {
  text-decoration: none;
  color: inherit;
}
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fafafa;
  padding: 10px 16px;
  border-bottom: 1px solid #ddd;
  font-family: sans-serif;
  font-size: 14px;
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.navbar-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;
  color: #333;
}

.title {
  font-weight: bold;
  color: #222;
}

.separator {
  color: #999;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #666;
}

.breadcrumbs .current {
  color: #111;
  font-weight: bold;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.icon-btn {
  font-size: 14px;
  background: none;
  border: none;
  cursor: pointer;
  color: #666;
}

.icon-btn:hover {
  color: #111;
}

.form-container {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 16px;
  font-family: sans-serif;
  font-size: 14px;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.form-title {
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-title .goto-link {
  color: #666;
  font-size: 13px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 4px;
  background: none;
}
.form-title .goto-link:hover {
  color: #111;
}

.form-icon {
    background-color: #8B5E3C;
    color: white;
    padding: 4px;
    display: inline-flex;
    font-size: 16px;
    border-radius: 50%;
}

.form-actions {
  display: flex;
  gap: 10px;
}

.btn {
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.btn.create {
  background-color: #80594c;
  color: white;
  border: none;
}

.btn.update,
.btn.clear {
  background-color: #f5f5f5;
}

.btn.danger {
  border: 1px solid #b91c1c;
  color: #b91c1c;
  background-color: white;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 12px;
  margin-bottom: 12px;
}

.form-grid input,
.form-grid select {
  padding: 6px 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.customer-select {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f3f3f3;
  padding: 6px 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.customer-select .x-btn {
  background: none;
  border: none;
  font-size: 16px;
  cursor: pointer;
}

.vat-toggle {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  position: relative;
}

.vat-toggle input {
  opacity: 0;
  width: 0;
  height: 0;
}

.vat-toggle .slider {
  position: relative;
  width: 36px;
  height: 20px;
  background-color: #ccc;
  border-radius: 34px;
  transition: background-color 0.2s;
}

.vat-toggle .slider::before {
  content: "";
  position: absolute;
  height: 14px;
  width: 14px;
  left: 3px;
  top: 3px;
  background-color: white;
  border-radius: 50%;
  transition: transform 0.2s;
}

.vat-toggle input:checked + .slider {
  background-color: #8c6751;
}

.vat-toggle input:checked + .slider::before {
  transform: translateX(16px);
}

.address-input {
  width: 100%;
  margin: 12px 0;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-summary {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 8px;
  padding: 8px 16px;
  border-top: 1px solid #ddd;
  overflow-x: auto;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 8px;
  border-radius: 6px;
  font-size: 12px; 
  font-family: sans-serif;
  white-space: nowrap;
}

.summary-label {
  background-color: #e0e0e0;
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 500;
  font-size: 12px;
  color: #333;
  white-space: nowrap;
}

.summary-value {
  font-weight: bold;
  color: #111;
  font-size: 12px;
  white-space: nowrap;
}

.product-table-wrapper {
  border: 1px solid #ccc;
  border-radius: 8px;
  font-family: sans-serif;
  background: #fff;
  padding: 16px;
}

.tab-header {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}

.tab {
  padding: 6px 12px;
  border: 1px solid #ccc;
  background: #f3f3f3;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.tab.active {
  background: #8b5e3c;
  color: white;
  border-color: #8b5e3c;
}

.table-container {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

th, td {
  border: 1px solid #e0e0e0;
  padding: 8px 6px;
  text-align: left;
  white-space: nowrap;
}

th {
  background-color: #f8f5f2;
  font-weight: bold;
}

input[type="text"] {
  width: 100%;
  padding: 4px 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 13px;
}

.btn-small {
  padding: 4px 8px;
  font-size: 12px;
  border: 1px solid #8b5e3c;
  background-color: white;
  color: #8b5e3c;
  border-radius: 4px;
  cursor: pointer;
}

.btn-small:hover {
  background-color: #f4ebe7;
}

.table-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 12px;
}

.btn-clear {
  background-color: #eee;
  color: #333;
  padding: 6px 12px;
  border: 1px solid #aaa;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
}

.btn-clear:hover {
  background-color: #ddd;
}
</style>
