<?php
$file = __DIR__ . '/../data/books.json';

$books = [];
if (file_exists($file)) {
    $books = json_decode(file_get_contents($file), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách sách</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between">
      <h4 class="mb-0">📚 Danh sách sách</h4>
      <a href="add_book.php" class="btn btn-light btn-sm">➕ Thêm sách</a>
    </div>

    <div class="card-body">
      <?php if (empty($books)): ?>
        <div class="alert alert-warning">Chưa có sách nào.</div>
      <?php else: ?>
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Mã</th>
              <th>Tên sách</th>
              <th>Tác giả</th>
              <th>Năm</th>
              <th>Thể loại</th>
              <th>Số lượng</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($books as $i => $b): ?>
            <tr>
              <td><?=$i+1?></td>
              <td><?=htmlspecialchars($b['code'])?></td>
              <td><?=htmlspecialchars($b['name'])?></td>
              <td><?=htmlspecialchars($b['author'])?></td>
              <td><?=htmlspecialchars($b['year'])?></td>
              <td><?=htmlspecialchars($b['cat'])?></td>
              <td>
                <span class="badge bg-<?=$b['qty']>0?'success':'danger'?>">
                  <?=$b['qty']?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
