<?php
session_start();

// Tắt tất cả thông báo lỗi
error_reporting(0);  // Tắt hiển thị lỗi
ini_set('display_errors', 0);  // Tắt hiển thị lỗi

include_once(__DIR__ . '/../config/db.php'); // Kết nối database

// Kiểm tra nếu giỏ hàng rỗng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
   
    header("Location: index.php");
    exit;
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang login
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Tổng tiền
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    // Kiểm tra nếu mỗi sản phẩm trong giỏ hàng có đủ thông tin
    if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
        exit;
    }
    $totalPrice += $item['price'] * $item['quantity'];  // Tính tổng tiền
}

// Lấy dữ liệu từ form
if (empty($_POST['fullname']) || empty($_POST['address']) || empty($_POST['phone'])) {
    
    exit;
}
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$phone = $_POST['phone'];

try {
    // Thêm đơn hàng vào bảng `orders`
    $stmt = $pdo->prepare("INSERT INTO orders (fullname, address, phone, total_price) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$fullname, $address, $phone, $totalPrice])) {
        $order_id = $pdo->lastInsertId(); // Lấy ID của đơn hàng vừa tạo

        // Thêm từng sản phẩm vào bảng `order_items`
        foreach ($_SESSION['cart'] as $item) {
            // Kiểm tra nếu các khóa cần thiết có trong mảng
            if (isset($item['name']) && isset($item['price']) && isset($item['quantity'])) {
                $sql = "INSERT INTO order_items (order_id, Name_product, Price_product, quantity) 
                        VALUES (:order_id, :Name_product, :Price_product, :quantity)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':order_id' => $order_id,
                    ':Name_product' => $item['name'],  // Tên sản phẩm
                    ':Price_product' => $item['price'], // Giá sản phẩm
                    ':quantity' => $item['quantity'] // Số lượng sản phẩm
                ]);
            }
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']); 

     
        header("Location: index.php");
        exit;
    } 
} catch (PDOException $e) {
    
    header("Location: index.php");
    exit;
}
?>