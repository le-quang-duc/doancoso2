<?php
include __DIR__ . '/../config/db.php';

$orderId = $_GET['id'];

try {
    // Xóa các sản phẩm liên quan đến đơn hàng
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);

    // Xóa đơn hàng
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);

    // Điều hướng lại về trang quản lý đơn hàng sau khi xóa thành công
    header("Location: dashboard.php#account");
    exit();
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>