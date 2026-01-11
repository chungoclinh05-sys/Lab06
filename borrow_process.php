<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: borrow_form.php');
    exit;
}

$member = trim($_POST['member'] ?? '');
$book   = trim($_POST['book'] ?? '');
$borrowDate = $_POST['borrow_date'] ?? '';
$days   = (int)($_POST['days'] ?? 0);

$errors = [];

// ===== ĐƯỜNG DẪN =====
$membersFile = __DIR__ . '/../data/members.csv';
$booksFile   = __DIR__ . '/../data/books.json';
$borrowsFile = __DIR__ . '/../data/borrows.json';

// ===== KIỂM TRA THÀNH VIÊN =====
$memberExists = false;
if (file_exists($membersFile) && ($fp = fopen($membersFile, 'r'))) {
    while (($row = fgetcsv($fp)) !== false) {
        if (trim($row[0]) === $member) {
            $memberExists = true;
            break;
        }
    }
    fclose($fp);
}
if (!$memberExists) {
    $errors[] = 'Mã thành viên không tồn tại';
}

// ===== KIỂM TRA FILE BOOKS =====
$books = json_decode(file_get_contents($booksFile), true) ?? [];
$bookIndex = -1;

foreach ($books as $i => $b) {
    if ($b['code'] === $book) {
        $bookIndex = $i;
        break;
    }
}

if ($bookIndex === -1) {
    $errors[] = 'Mã sách không tồn tại';
} elseif ($books[$bookIndex]['qty'] <= 0) {
    $errors[] = 'Sách đã hết';
}

// ===== KIỂM TRA SỐ NGÀY =====
if ($days < 1 || $days > 30) {
    $errors[] = 'Số ngày mượn phải từ 1 đến 30';
}

// ===== NẾU CÓ LỖI → GIAO DIỆN ĐẸP =====
if ($errors) {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
    <meta charset="UTF-8">
    <title>Lỗi mượn sách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container mt-5" style="max-width:600px;">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white">
                ❌ Không thể mượn sách
            </div>
            <div class="card-body">
                <ul class="mb-3">
                    <?php foreach ($errors as $e): ?>
                        <li><?=htmlspecialchars($e)?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="borrow_form.php" class="btn btn-secondary">⬅ Quay lại</a>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// ===== TẠO FILE BORROWS NẾU CHƯA CÓ =====
if (!file_exists($borrowsFile)) {
    file_put_contents($borrowsFile, json_encode([]));
}

$borrows = json_decode(file_get_contents($borrowsFile), true) ?? [];

// ===== TÍNH HẠN TRẢ =====
$dueDate = date('Y-m-d', strtotime("$borrowDate +$days days"));
$borrowId = 'BR' . time();

// ===== LƯU PHIẾU =====
$borrows[] = [
    'id' => $borrowId,
    'member' => $member,
    'book' => $book,
    'borrow_date' => $borrowDate,
    'due_date' => $dueDate,
    'status' => 'Đang mượn'
];

file_put_contents(
    $borrowsFile,
    json_encode($borrows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// ===== GIẢM SỐ LƯỢNG SÁCH =====
$books[$bookIndex]['qty']--;
file_put_contents(
    $booksFile,
    json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Mượn sách thành công</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:600px;">
    <div class="card shadow border-success">
        <div class="card-header bg-success text-white">
            ✅ Mượn sách thành công
        </div>
        <div class="card-body">
            <p><b>Mã phiếu:</b> <?=htmlspecialchars($borrowId)?></p>
            <p><b>Hạn trả:</b> <?=htmlspecialchars($dueDate)?></p>
            <a href="borrow_form.php" class="btn btn-primary">📖 Mượn tiếp</a>
        </div>
    </div>
</div>

</body>
</html>
