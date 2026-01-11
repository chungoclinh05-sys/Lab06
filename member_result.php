<?php
// ================== CHẶN TRUY CẬP TRỰC TIẾP ==================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p>Không được truy cập trực tiếp trang này.</p>";
    echo "<a href='register_member.php'>Quay lại form</a>";
    exit;
}

// ================== LẤY DỮ LIỆU AN TOÀN ==================
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$dob     = trim($_POST['dob'] ?? '');
$gender  = trim($_POST['gender'] ?? '');
$address = trim($_POST['address'] ?? '');

$errors = [];

// ================== VALIDATE ==================
if ($name === '')  $errors[] = 'Họ tên không được để trống';
if ($email === '') $errors[] = 'Email không được để trống';
if ($phone === '') $errors[] = 'Số điện thoại không được để trống';
if ($dob === '')   $errors[] = 'Ngày sinh không được để trống';

if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email không đúng định dạng';
}

if ($phone && !preg_match('/^\d{9,11}$/', $phone)) {
    $errors[] = 'Số điện thoại phải gồm 9–11 chữ số';
}

// ================== NẾU CÓ LỖI → QUAY LẠI FORM ==================
if (!empty($errors)) {
    $query = http_build_query([
        'errors'  => json_encode($errors),
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone,
        'dob'     => $dob,
        'gender'  => $gender,
        'address' => $address
    ]);
    header("Location: register_member.php?$query");
    exit;
}

// ================== TẠO MÃ THÀNH VIÊN ==================
$memberCode = 'TV' . time();

// ================== LƯU CSV (AN TOÀN) ==================
$file = __DIR__ . '/../data/members.csv';

// Tạo thư mục nếu chưa có
if (!file_exists(dirname($file))) {
    mkdir(dirname($file), 0777, true);
}

$fp = fopen($file, 'a');
if ($fp === false) {
    die('Không mở được file members.csv');
}

// Ghi theo thứ tự: MÃ | HỌ TÊN | EMAIL | SĐT | NGÀY SINH | GIỚI TÍNH | ĐỊA CHỈ
fputcsv($fp, [$memberCode, $name, $email, $phone, $dob, $gender, $address]);
fclose($fp);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Kết quả đăng ký</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 700px;">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">✅ Đăng ký thành công</h4>
    </div>
    <div class="card-body">

      <ul class="list-group">
        <li class="list-group-item">
          <b>Mã thành viên:</b>
          <?=htmlspecialchars($memberCode, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>Họ tên:</b>
          <?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>Email:</b>
          <?=htmlspecialchars($email, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>SĐT:</b>
          <?=htmlspecialchars($phone, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>Ngày sinh:</b>
          <?=htmlspecialchars($dob, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>Giới tính:</b>
          <?=htmlspecialchars($gender, ENT_QUOTES, 'UTF-8')?>
        </li>
        <li class="list-group-item">
          <b>Địa chỉ:</b>
          <?=htmlspecialchars($address, ENT_QUOTES, 'UTF-8')?>
        </li>
      </ul>

      <div class="mt-4 text-end">
        <a href="register_member.php" class="btn btn-primary">Đăng ký tiếp</a>
      </div>

    </div>
  </div>
</div>

</body>
</html>
