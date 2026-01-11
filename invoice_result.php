<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: invoice_form.php');
    exit;
}

// ===== LẤY DỮ LIỆU =====
$name  = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$phone = trim($_POST['customer_phone'] ?? '');

$itemNames  = $_POST['item_name'] ?? [];
$itemQtys   = $_POST['item_qty'] ?? [];
$itemPrices = $_POST['item_price'] ?? [];

$discountPercent = (float)($_POST['discount'] ?? 0);
$vatPercent      = (float)($_POST['vat'] ?? 0);
$payment         = $_POST['payment'] ?? '';

$errors = [];
$items = [];

// ===== VALIDATE =====
if ($name === '' || $phone === '') {
    $errors[] = 'Họ tên và SĐT là bắt buộc';
}

// Lọc các dòng hàng hợp lệ
for ($i = 0; $i < count($itemNames); $i++) {
    $iname = trim($itemNames[$i]);
    $qty   = (int)$itemQtys[$i];
    $price = (float)$itemPrices[$i];

    if ($iname !== '' && $qty > 0 && $price > 0) {
        $items[] = [
            'name' => $iname,
            'qty'  => $qty,
            'price'=> $price,
            'total'=> $qty * $price
        ];
    }
}

if (count($items) === 0) {
    $errors[] = 'Phải có ít nhất 1 dòng hàng hợp lệ';
}

if ($discountPercent < 0 || $discountPercent > 30) {
    $errors[] = 'Giảm giá phải từ 0–30%';
}

if ($vatPercent < 0 || $vatPercent > 15) {
    $errors[] = 'VAT phải từ 0–15%';
}

// ===== NẾU CÓ LỖI =====
if ($errors) {
    echo "<h3>Lỗi</h3><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><a href='invoice_form.php'>Quay lại</a>";
    exit;
}

// ===== TÍNH TOÁN =====
$subtotal = 0;
foreach ($items as $it) {
    $subtotal += $it['total'];
}

$discount = $subtotal * $discountPercent / 100;
$afterDiscount = $subtotal - $discount;
$vat = $afterDiscount * $vatPercent / 100;
$grandTotal = $afterDiscount + $vat;

// ===== LƯU FILE =====
$invoiceData = [
    'customer' => [
        'name' => $name,
        'email'=> $email,
        'phone'=> $phone
    ],
    'items' => $items,
    'subtotal' => $subtotal,
    'discount_percent' => $discountPercent,
    'vat_percent' => $vatPercent,
    'total' => $grandTotal,
    'payment' => $payment,
    'created_at' => date('Y-m-d H:i:s')
];

$dir = __DIR__ . '/../data/invoices';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$file = $dir . '/invoice_' . time() . '.json';
file_put_contents(
    $file,
    json_encode($invoiceData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// ===== HÀM FORMAT TIỀN =====
function vnd($n) {
    return number_format($n, 0, ',', '.') . ' đ';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hóa đơn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:900px;">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">🧾 HÓA ĐƠN BÁN HÀNG</h4>
    </div>
    <div class="card-body">

      <p><b>Khách hàng:</b> <?=htmlspecialchars($name)?></p>
      <p><b>SĐT:</b> <?=htmlspecialchars($phone)?></p>
      <p><b>Phương thức:</b> <?=htmlspecialchars($payment)?></p>

      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>Mặt hàng</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
          <tr>
            <td><?=htmlspecialchars($it['name'])?></td>
            <td><?=$it['qty']?></td>
            <td><?=vnd($it['price'])?></td>
            <td><?=vnd($it['total'])?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="row justify-content-end">
        <div class="col-md-4">
          <table class="table">
            <tr>
              <th>Tạm tính</th>
              <td><?=vnd($subtotal)?></td>
            </tr>
            <tr>
              <th>Giảm giá</th>
              <td><?=vnd($discount)?></td>
            </tr>
            <tr>
              <th>VAT</th>
              <td><?=vnd($vat)?></td>
            </tr>
            <tr class="table-success">
              <th>Tổng thanh toán</th>
              <td><b><?=vnd($grandTotal)?></b></td>
            </tr>
          </table>
        </div>
      </div>

      <div class="text-end">
        <a href="invoice_form.php" class="btn btn-primary">➕ Tạo hóa đơn mới</a>
      </div>

    </div>
  </div>
</div>

</body>
</html>