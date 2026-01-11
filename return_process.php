<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Không được truy cập trực tiếp.";
    exit;
}

$borrowId = trim($_POST['borrow_id'] ?? '');

$booksFile   = __DIR__ . '/../data/books.json';
$borrowsFile = __DIR__ . '/../data/borrows.json';

$borrows = json_decode(file_get_contents($borrowsFile), true) ?? [];
$books   = json_decode(file_get_contents($booksFile), true) ?? [];

$index = -1;
foreach ($borrows as $i => $b) {
    if ($b['id'] === $borrowId && $b['status'] === 'Đang mượn') {
        $index = $i;
        break;
    }
}

if ($index === -1) {
    echo "Phiếu không tồn tại hoặc đã trả.<br>";
    echo "<a href='return_form.php'>Quay lại</a>";
    exit;
}

// ===== TĂNG LẠI SỐ LƯỢNG SÁCH =====
foreach ($books as &$book) {
    if ($book['code'] === $borrows[$index]['book']) {
        $book['qty']++;
        break;
    }
}

// ===== CẬP NHẬT TRẠNG THÁI =====
$borrows[$index]['status'] = 'Đã trả';

file_put_contents(
    $booksFile,
    json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

file_put_contents(
    $borrowsFile,
    json_encode($borrows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo "<h3>✅ Trả sách thành công</h3>";
echo "<p>Mã phiếu: <b>$borrowId</b></p>";
echo "<a href='return_form.php'>Trả tiếp</a>";
