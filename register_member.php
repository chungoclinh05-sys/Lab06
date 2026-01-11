<?php
// Giữ lại dữ liệu cũ khi lỗi
$old = $_GET;

// Hàm lấy giá trị cũ an toàn
function old($key) {
    return htmlspecialchars($GLOBALS['old'][$key] ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký thẻ thư viện</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 700px;">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">📚 Đăng ký thẻ thư viện</h4>
    </div>

    <div class="card-body">

      <!-- HIỂN THỊ LỖI -->
      <?php if (isset($_GET['errors'])): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach (json_decode($_GET['errors'], true) as $err): ?>
              <li><?=htmlspecialchars($err, ENT_QUOTES, 'UTF-8')?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="member_result.php">

        <div class="mb-3">
          <label class="form-label">Họ tên <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="<?=old('name')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" value="<?=old('email')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" value="<?=old('phone')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
          <input type="date" name="dob" class="form-control" value="<?=old('dob')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Giới tính</label><br>
          <?php foreach (['Nam','Nữ','Khác'] as $g): ?>
            <label class="me-3">
              <input type="radio" name="gender" value="<?=$g?>" <?=old('gender')===$g?'checked':''?>> <?=$g?>
            </label>
          <?php endforeach; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Địa chỉ</label>
          <textarea name="address" class="form-control" rows="3"><?=old('address')?></textarea>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">Đăng ký</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>
