# Test Yubi Apps

Project ini dibuat untuk memenuhi tes teknikal.

---

## TechStack

- Frontend : PHP Native
- Backend : Laravel
- Database : Postgresql

---

## Fitur

### Backend
- CRUD untuk:
  - Sales Orders (`sales_orders`)
  - Sales Order Details (`so_dts`)
### Frontend
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
  "discount_value": 10000,
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
php artisan serve
```
2. Jalankan frontend
```powershell
cd .\test-yubi-frontend\
php -S localhost:8080
```

---
 ## Tambahan

 Ada file yang bernama sales_order_dummy.json digunakan untuk isi awal dalam backedn setelah running

 ```json
{
  "so_number": "SO-2025-0001",
  "so_date": "2025-04-17",
  "ship_date": "2025-04-24",
  "customer_id": 1,
  "currency_id": 1,
  "order_type": 1,
  "status": "open",
  "vat_id": 0,
  "pph23_id": 0,
  "ship_dest": "Buyer 1 Address",
  "discount_value": 0,
  "discount_type": null,
  "soDts": [
    {
      "product_uuid": "uuid-pc-server-a",
      "ref_type": "Products",
      "quantity": 1,
      "price": 10000000,
      "disc_perc": 0,
      "disc_am": 0,
      "total_am": 10000000,
      "remark": ""
    },
    {
      "product_uuid": "uuid-psu-500w",
      "ref_type": "Products",
      "quantity": 4,
      "price": 350000,
      "disc_perc": 0,
      "disc_am": 0,
      "total_am": 1400000,
      "remark": ""
    }
  ]
}
 ```
- Hit API ini setelah menjalankan `php artisan serve`

```curl
| POST   | `/api/sales-orders` | 
```

 ---