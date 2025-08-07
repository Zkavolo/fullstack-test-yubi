# Test Yubi Apps

Project ini dibuat untuk memenuhi tes teknikal.

---

## TechStack

- Front End : PHP Native
- Back End : Laravel
- Database : Postgresql

---

## Fitur

- CRUD untuk:
  - Sales Orders (`sales_orders`)
  - Sales Order Details (`so_dts`)

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
