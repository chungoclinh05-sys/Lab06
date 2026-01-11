<?php
// Đường dẫn file JSON (an toàn tuyệt đối)
$file = __DIR__ . '/../data/books.json';

// Tạo file nếu chưa tồn tại
if (!file_exists($file)) {
    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }
    file_put_contents($file, json_encode([]));
}

// Đọc dữ liệu sách
$books = json_decode(file_get_contents($file), true) ?? [];

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code   = trim($_POST['code'] ?? '');
    $name   = trim($_POST['name'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year   = (int)($_POST['year'] ?? 0);
    $cat    = $_POST['category'] ?? '';
    $qty    = (int)($_POST['qty'] ?? -1);

    $old = $_POST;

    // Validate
    if ($code === '')   $errors[] = 'Mã sách không được để trống';
    if ($name === '')   $errors[] = 'Tên sách không được để trống';
    if ($author === '') $errors[] = 'Tác giả không được để trống';

    $currentYear = date('Y');
    if ($year < 1900 || $year > $currentYear) {
        $errors[] = "Năm xuất bản phải từ 1900 đến $currentYear";
    }

    if ($qty < 0) {
        $errors[] = 'Số lượng phải >= 0';
    }

    // Check trùng mã sách
    foreach ($books as $b) {
        if ($b['code'] === $code) {
            $errors[] = 'Mã sách đã tồn tại';
            break;
        }
    }

    // Nếu hợp lệ → lưu
    if (empty($errors)) {
        $books[] = [
            'code'   => $code,
            'name'   => $name,
            'author' => $author,
            'year'   => $year,
            'cat'    => $cat,
            'qty'    => $qty
        ];

        file_put_contents(
            $file,
            json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        header('Location: list_books.php');
        exit;
    }
}

// Hàm giữ lại dữ liệu cũ
function old($key) {
    return htmlspecialchars($GLOBALS['old'][$key] ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm sách</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 750px;">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">📘 Thêm sách vào kho</h4>
    </div>
    <div class="card-body">

      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?=htmlspecialchars($e, ENT_QUOTES, 'UTF-8')?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Mã sách *</label>
          <input type="text" name="code" class="form-control" value="<?=old('code')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Tên sách *</label>
          <input type="text" name="name" class="form-control" value="<?=old('name')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Tác giả *</label>
          <input type="text" name="author" class="form-control" value="<?=old('author')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Năm xuất bản *</label>
          <input type="number" name="year" class="form-control" value="<?=old('year')?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Thể loại</label>
          <select name="category" class="form-select">
            <?php
            $cats = ['Giáo trình','Kỹ năng','Văn học','Khoa học','Khác'];
            foreach ($cats as $c):
            ?>
              <option value="<?=$c?>" <?=old('category')===$c?'selected':''?>><?=$c?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Số lượng *</label>
          <input type="number" name="qty" class="form-control" value="<?=old('qty')?>">
        </div>

        <div class="text-end">
          <button class="btn btn-primary">Lưu sách</button>
          <a href="list_books.php" class="btn btn-secondary">Xem danh sách</a>
        </div>
      </form>

    </div>
  </div>
</div>

</body>
</html>
