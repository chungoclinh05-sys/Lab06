<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Mượn sách</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">📖 Phiếu mượn sách</h4>
    </div>
    <div class="card-body">

      <form method="post" action="borrow_process.php">
        <div class="mb-3">
          <label class="form-label">Mã thành viên</label>
          <input type="text" name="member" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Mã sách</label>
          <input type="text" name="book" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Ngày mượn</label>
          <input type="date" name="borrow_date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Số ngày mượn (1–30)</label>
          <input type="number" name="days" class="form-control" min="1" max="30" required>
        </div>

        <div class="text-end">
          <button class="btn btn-primary">Mượn sách</button>
        </div>
      </form>

    </div>
  </div>
</div>

</body>
</html>

