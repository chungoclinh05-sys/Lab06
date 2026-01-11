<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== ĐƯỜNG DẪN FILE =====
$file = __DIR__ . '/../data/books.json';

// ===== ĐỌC DỮ LIỆU =====
$books = [];
if (file_exists($file)) {
    $books = json_decode(file_get_contents($file), true);
    if (!is_array($books)) $books = [];
}

// ===== LẤY GIÁ TRỊ GET =====
$kw        = trim($_GET['kw'] ?? '');
$category  = $_GET['category'] ?? 'all';
$yearFrom  = (int)($_GET['year_from'] ?? 0);
$yearTo    = (int)($_GET['year_to'] ?? 0);

// ===== LỌC DỮ LIỆU =====
$results = [];
foreach ($books as $b) {

    if ($kw !== '') {
        $text = strtolower($b['name'] . ' ' . $b['author']);
        if (strpos($text, strtolower($kw)) === false) {
            continue;
        }
    }

    if ($category !== 'all' && $b['cat'] !== $category) {
        continue;
    }

    if ($yearFrom > 0 && $b['year'] < $yearFrom) continue;
    if ($yearTo > 0 && $b['year'] > $yearTo) continue;

    $results[] = $b;
}

// ===== ESCAPE =====
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$categories = ['Giáo trình','Kỹ năng','Văn học','Khoa học','Khác'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tìm kiếm sách</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">

    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">🔍 Tìm kiếm & lọc sách</h4>
    </div>

    <div class="card-body">

      <!-- FORM SEARCH (GET) -->
      <form method="get" class="row g-3 mb-4">

        <div class="col-md-4">
          <label class="form-label">Từ khóa</label>
          <input type="text" name="kw" class="form-control" value="<?=e($kw)?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">Thể loại</label>
          <select name="category" class="form-select">
            <option value="all">-- Tất cả --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?=e($c)?>" <?=($category===$c)?'selected':''?>>
                <?=e($c)?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Năm từ</label>
          <input type="number" name="year_from" class="form-control" value="<?=e($yearFrom)?>">
        </div>

        <div class="col-md-2">
          <label class="form-label">Năm đến</label>
          <input type="number" name="year_to" class="form-control" value="<?=e($yearTo)?>">
        </div>

        <div class="col-md-1 d-flex align-items-end">
          <button class="btn btn-success w-100">Search</button>
        </div>

      </form>

      <!-- KẾT QUẢ -->
      <?php if ($_GET): ?>
        <?php if (empty($results)): ?>
          <div class="alert alert-warning">❌ Không có kết quả</div>
        <?php else: ?>
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Mã</th>
                <th>Tên</th>
                <th>Tác giả</th>
                <th>Năm</th>
                <th>Thể loại</th>
                <th>Số lượng</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $i => $b): ?>
              <tr>
                <td><?=$i+1?></td>
                <td><?=e($b['code'])?></td>
                <td><?=e($b['name'])?></td>
                <td><?=e($b['author'])?></td>
                <td><?=e($b['year'])?></td>
                <td><?=e($b['cat'])?></td>
                <td><?=e($b['qty'])?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>
