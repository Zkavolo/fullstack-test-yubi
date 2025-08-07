<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>

<div class="sidebar">
  <div class="logo m-3"><i data-feather="grid"></i></div>
  <a href="#" class="border"><i class="flipped-horizontal" data-feather="tool"></i></a>
  <a href="#"><i data-feather="shopping-cart"></i></a>
  <a href="#"><i data-feather="credit-card"></i></a>
  <a href="#"><i data-feather="shopping-cart"></i></a>
  <a href="#"><i data-feather="home"></i></a>
  <a href="#"><i data-feather="file-plus"></i></a>
</div>

<div class="topbar">
  <div class="d-flex align-items-center mb-3">
  <i data-feather="align-justify" class="me-2"></i>
  <div>
    <p class="mb-1 fw-bold">Sales Orders</p>
    <p class="mb-0">
      Sales &gt; Sales Orders &gt; 
      <span class="custom-text-color-2">Edit</span> &gt; 
      <span class="custom-text-color-2">1</span>
    </p>
  </div>
</div>

  <div>
    <button class="btn btn-light btn-sm" disabled><i data-feather="sun"></i></button>
    <button class="btn btn-light btn-sm" disabled><i data-feather="log-out"></i></button>
  </div>
</div>

  <div class="form-section">
    <div class="section-label">
      <div>
        Basic Information
      </div>
      <div class="d-flex align-items-center">
    </div>
    <div class="row g-3">
      <div class="col-md-3">
        <label class="label-title">PO Buyer No</label>
        <input type="text" class="form-control" id="po_number" placeholder="Buy 1/2025-04-1/REV-5">
      </div>
      <div class="col-md-3">
        <label class="label-title">Order Type</label>
        <input type="text" class="form-control" id="order_type" value="Sales" readonly>
      </div>
      <div class="col-md-3">
        <label class="label-title">Order Date</label>
        <input type="date" class="form-control" id="so_date" value="2025-04-17">
      </div>
      <div class="col-md-3">
        <label class="label-title">Shipping Date</label>
        <input type="date" class="form-control" id="ship_date" value="2025-04-24">
      </div>
      <div class="col-md-4">
        <label class="label-title">Customer</label>
        <input type="text" class="form-control" id="customer" value="Buyer 1">
      </div>
      <div class="col-md-4">
        <label class="label-title">Email</label>
        <input type="email" class="form-control" id="email" value="buy@gmail.com">
      </div>
      <div class="col-md-2">
        <label class="label-title">Phone</label>
        <input type="text" class="form-control" id="phone" value="4444444444">
      </div>
      <div class="col-md-2">
        <label class="label-title">Status</label>
        <select class="form-select" id="status">
          <option selected>PROCESS</option>
          <option>DRAFT</option>
          <option>COMPLETED</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="label-title">Currency</label>
        <select class="form-select" id="currency">
          <option selected>IDR</option>
          <option >USD</option>
          <option >SGD</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="label-title">Exchange Rate</label>
        <input type="text" class="form-control" id="exchange_rate" value="1.000">
      </div>
      <div class="col-md-3">
        <label class="label-title">PPH (%)</label>
        <input type="text" class="form-control" id="pph" placeholder="0.00">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="vatToggle">
          <label class="form-check-label" for="vatToggle">VAT</label>
        </div>
      </div>
    </div>
    <div class="mt-3">
      <input type="text" class="form-control" value="Buyer 1 Address" placeholder="Buyer Address">
    </div>
    <div class="row text-center mt-3">
      <div class="col-md-2">Sub Amount<br><strong id="sub_amount">11,400,000.00</strong></div>
      <div class="col-md-2">Total Discount<br><strong>0.00</strong></div>
      <div class="col-md-2">After Discount<br><strong>11,400,000.00</strong></div>
      <div class="col-md-2">Total VAT<br><strong>0.00</strong></div>
      <div class="col-md-2">Total PPH23<br><strong>0.00</strong></div>
      <div class="col-md-2">Grand Total<br><strong id="grand_total">11,400,000.00</strong></div>
    </div>
  </div>

  <div class="form-section">
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item">
        <a class="nav-link active" href="#">ITEMS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">REMARK</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">SCHEDULE</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">ATTACHMENTS</a>
      </li>
    </ul>

    <div class="mb-2">
      <button class="btn btn-outline-dark btn-sm me-2"><i data-feather="box"></i> Ms. Product (2)</button>
      <button class="btn btn-outline-dark btn-sm"><i data-feather="flag"></i> Quotation (0)</button>
    </div>

    <table class="table table-bordered mt-3">
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
      <tbody id="items-body">
        <tr>
          <td>Products</td>
          <td></td>
          <td>Product</td>
          <td>CPC002</td>
          <td>PC Server A</td>
          <td>PIECE</td>
          <td>10,000,000.000</td>
          <td>1.000</td>
          <td>0.000</td>
          <td>0.000</td>
          <td>10,000,000.000</td>
          <td><input class="form-control form-control-sm" placeholder="Remark"></td>
          <td><button class="btn btn-sm btn-outline-danger"><i data-feather="trash-2"></i></button></td>
        </tr>
        <tr>
          <td>Products</td>
          <td></td>
          <td>Item</td>
          <td>0011</td>
          <td>PSU 500W KKK</td>
          <td>PIECE</td>
          <td>350,000.000</td>
          <td>4.000</td>
          <td>0.000</td>
          <td>0.000</td>
          <td>1,400,000.000</td>
          <td><input class="form-control form-control-sm" placeholder="Remark"></td>
          <td><button class="btn btn-sm btn-outline-danger"><i data-feather="trash-2"></i></button></td>
        </tr>
      </tbody>
    </table>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>feather.replace();</script>
<script src="data/data.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>