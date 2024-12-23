<?php
include __DIR__ . '/../config/db.php'; // Đảm bảo đường dẫn chính xác đến db.php

// Kiểm tra xem có ID sản phẩm không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID sản phẩm không hợp lệ.");
}

$product_id = $_GET['id'];

// Truy vấn để lấy thông tin sản phẩm trước khi xóa (để kiểm tra và xóa ảnh nếu có)
$result = $connect->query("SELECT * FROM admin_shop WHERE id = $product_id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Không tìm thấy sản phẩm.");
}

// Lấy đường dẫn ảnh sản phẩm cũ (nếu có) để xóa file ảnh
$image_path = $product['Image_product'];

// Xóa sản phẩm trong cơ sở dữ liệu
$stmt = $connect->prepare("DELETE FROM admin_shop WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // Sau khi xóa sản phẩm, xóa ảnh nếu có
    if (file_exists($image_path)) {
        unlink($image_path); // Xóa ảnh từ thư mục
    }

    // Quay lại trang quản lý sản phẩm sau khi xóa thành công
    header("Location: dashboard.php");
    exit();
} else {
    echo "Lỗi khi xóa sản phẩm: " . $stmt->error;
}

$stmt->close();
?>