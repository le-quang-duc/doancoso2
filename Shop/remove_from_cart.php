<?php
session_start();
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Kiểm tra nếu sản phẩm có trong giỏ hàng và xóa nó
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Tính lại tổng giá giỏ hàng và trả lại cho AJAX
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $product) {
        $totalPrice += $product['price'] * $product['quantity'];
    }

    // Trả lại tổng giá cho AJAX
    echo number_format($totalPrice, 0, ',', '.') . ' VND';
}
?>