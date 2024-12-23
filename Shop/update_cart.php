<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update') {
        $id = $_POST['id'];
        $newQuantity = $_POST['quantity'];

        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$id])) {
            // Cập nhật số lượng sản phẩm
            $_SESSION['cart'][$id]['quantity'] = $newQuantity;

            // Tính lại subtotal cho sản phẩm
            $productPrice = $_SESSION['cart'][$id]['price'];
            $subtotal = $productPrice * $newQuantity;

            // Tính lại tổng tiền giỏ hàng
            $totalPrice = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            // Trả về kết quả
            echo json_encode([
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($totalPrice, 0, ',', '.')
            ]);
        } else {
            echo json_encode(['error' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
        }
    } elseif ($action === 'remove') {
        $id = $_POST['id'];

        // Xóa sản phẩm khỏi giỏ hàng
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        // Tính lại tổng tiền giỏ hàng
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Trả về kết quả
        echo json_encode(['total' => number_format($totalPrice, 0, ',', '.')]);
    }
}
?>