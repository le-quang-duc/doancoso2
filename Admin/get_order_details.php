<?php
include __DIR__ . '/../config/db.php';

$orderId = $_GET['id'];
$response = [];

// Lấy thông tin đơn hàng
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order) {
    // Lấy danh sách sản phẩm trong đơn hàng
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về thông tin đơn hàng và các sản phẩm
    $response['id'] = $order['id'];
    $response['fullname'] = $order['fullname'];
    $response['address'] = $order['address'];
    $response['phone'] = $order['phone'];
    $response['total_price'] = $order['total_price'];
    $response['created_at'] = $order['created_at'];
    $response['products'] = $products;
}

echo json_encode($response);
?>