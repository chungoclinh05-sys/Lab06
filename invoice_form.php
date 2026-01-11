<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tạo hóa đơn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 900px;">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">🧾 Tạo hóa đơn bán hàng</h4>
    </div>
    <div class="card-body">

      <form method="post" action="invoice_result.php">

        <!-- THÔNG TIN KHÁCH -->
        <h5 class="mb-3">👤 Thông tin khách hàng</h5>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Họ tên</label>
            <input type="text" name="customer_name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="customer_email" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="customer_phone" class="form-control" required>
          </div>
        </div>

        <!-- HÀNG HÓA -->
        <h5 class="mb-3">🛒 Danh sách hàng hóa</h5>

        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Tên hàng</th>
              <th style="width:120px;">Số lượng</th>
              <th style="width:150px;">Đơn giá (đ)</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 0; $i < 3; $i++): ?>
            <tr>
              <td><input type="text" name="item_name[]" class="form-control"></td>
              <td><input type="number" name="item_qty[]" class="form-control" min="0"></td>
              <td><input type="number" name="item_price[]" class="form-control" min="0"></td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>

        <!-- THANH TOÁN -->
        <h5 class="mb-3">💰 Thanh toán</h5>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Giảm giá (%)</label>
            <input type="number" name="discount" class="form-control" value="0" min="0" max="30">
          </div>
          <div class="col-md-4">
            <label class="form-label">VAT (%)</label>
            <input type="number" name="vat" class="form-control" value="0" min="0" max="15">
          </div>
          <div class="col-md-4">
            <label class="form-label">Phương thức</label><br>
            <label class="me-3">
              <input type="radio" name="payment" value="Tiền mặt" checked> Tiền mặt
            </label>
            <label>
              <input type="radio" name="payment" value="Chuyển khoản"> Chuyển khoản
            </label>
          </div>
        </div>

        <div class="text-end">
          <button class="btn btn-success btn-lg">Tạo hóa đơn</button>
        </div>

      </form>

    </div>
  </div>
</div>

</body>
</html>
