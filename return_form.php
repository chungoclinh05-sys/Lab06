<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trả sách</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
  <div class="card shadow">
    <div class="card-header bg-warning">
      <h4 class="mb-0">🔁 Trả sách</h4>
    </div>
    <div class="card-body">

      <form method="post" action="return_process.php">
        <div class="mb-3">
          <label class="form-label">Mã phiếu mượn</label>
          <input type="text" name="borrow_id" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Ngày trả</label>
          <input type="date" name="return_date" class="form-control" required>
        </div>

        <div class="text-end">
          <button class="btn btn-warning">Trả sách</button>
        </div>
      </form>

    </div>
  </div>
</div>

</body>
</html>
