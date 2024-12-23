<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // Kết nối cơ sở dữ liệu
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit;
}
// Kiểm tra xem sản phẩm có trong giỏ hàng không
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM admin_shop WHERE id = ?";
    $stmt = $connect->prepare($sql);

    // Bind tham số và thực thi
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Nếu sản phẩm tồn tại
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Nếu giỏ hàng chưa được tạo, khởi tạo giỏ hàng
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
        if (isset($_SESSION['cart'][$product_id])) {
            // Nếu có rồi thì tăng số lượng lên
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            // Nếu chưa có thì thêm sản phẩm vào giỏ hàng
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['Name_product'],
                'price' => $product['Price_product'],
                'image' => $product['Image_product'],
                'quantity' => 1
            ];
        }
    }
}

// Chuyển hướng về trang giỏ hàng
header("Location: shoping-cart.php");
exit();
?>