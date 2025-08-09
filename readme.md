# Test Yubi Apps

Project ini dibuat untuk memenuhi tes teknikal.

---

## TechStack

- Frontend : PHP Native / Nuxtjs
- Backend : Laravel
- Database : Postgresql

---

## Fitur

### Backend
- CRUD untuk:
  - Sales Orders (`sales_orders`)
  - Sales Order Details (`so_dts`)
- Seeder untuk memudahkan pemasukkan data awal ke database

### Frontend Nuxtjs
- CRUD untuk:
  - Sales Orders (`sales_orders`)
  - Sales Order Details (`so_dts`)
- routing untuk navigasi ke dashboard sales order dan sales order details
- beberapa modal yang dengan css benar2x minim untuk proses create
- update tidak ada untuk Sales order cuman Sales order details

### Frontend PHP
- UI menggunakan PHP Native + Bootstrap 5

- Halaman:

  - (index.php) – menampilkan tampilan aplikasi

- Komponen:

  - Sidebar navigasi kiri

  - Topbar header

  -  Form Sales Order dengan field:

- Menggunakan Feather Icons untuk ikon

- File JavaScript terpisah di assets/js/script.js

- Data referensi statis disimpan di data/data.js

- Data dari backend cuman menggunakan logic get dengan Sales Order id yang hard coded bisa dicheck pada script.js

- Web tidak responsive
---

## Endpoint API

### Sales Orders

| Method | Endpoint                      | Description                           |
|--------|-------------------------------|---------------------------------------|
| GET    | `/api/sales-orders`           | List semua sales order                |
| GET    | `/api/sales-orders/{id}`      | Mendapatkan satu sales order          |
| POST   | `/api/sales-orders`           | Membuat satu sales order baru         |
| PUT    | `/api/sales-orders/{id}`      | Update sales order yang sudah ada     |
| DELETE | `/api/sales-orders/{id}`      | Menghapus sales order yang sudah ada  |

---

### Sales Order Details (SoDts)

| Method | Endpoint                                             | Description                                  |
|--------|------------------------------------------------------|----------------------------------------------|
| GET    | `/api/sales-orders/{sales_order}/details`            | List semua SoDt pada sales satu order        |
| GET    | `/api/sales-orders/{sales_order}/details/{id}`       | Mendapatkan satu SoDt pada satu sales order  |
| POST   | `/api/sales-orders/{sales_order}/details`            | Membuat satu SoDt baru pada satu sales order |
| PUT    | `/api/sales-orders/{sales_order}/details/{id}`       | Update sales Sodt yang sudah ada             |
| DELETE | `/api/sales-orders/{sales_order}/details/{id}`       | Menghapus Sodt yang sudah ada                |

---

## Contoh : Membuat satu sales order baru

**POST** `/api/sales-orders`

```json
{
  "so_number": "SO-2025-0001",
  "so_date": "2025-08-07",
  "ship_date": "2025-08-10",
  "customer_id": 1,
  "currency_id": 1,
  "status": "open",
  "order_type": 1,
  "vat_id": 1,
  "pph23_id": 1,
  "ship_dest": "Jakarta",
  "discount_type": "nominal",
  "soDts": [
    {
      "product_uuid": "PRD-001",
      "ref_type": "Products",
      "disc_perc": 0,
      "disc_am": 10000,
      "quantity": 5,
      "price": 200000,
      "remark": "Item 1"
    },
    {
      "product_uuid": "PRD-002",
      "ref_type": "Products",
      "disc_perc": 0,
      "disc_am": 0,
      "quantity": 2,
      "price": 100000,
      "remark": "Item 2"
    }
  ]
}
```
---
## Cara Menjalakan aplikasi
1. Jalankan backend
```powershell
cd .\test-yubi-backend\
php artisan migrate
php artisan db:seed --class=SalesOrderSeeder
php artisan serve
```

2. Jalankan frontend Nuxtjs
```powershell
cd .\nuxt-api\test-yubi-nuxt-js\
npm i
npm run dev
```

3. Jalankan frontend PHP
```powershell
cd .\test-yubi-frontend\
php -S localhost:8080
```

 ---