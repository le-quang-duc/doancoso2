<?php
session_start();
include __DIR__ . '/../config/db.php'; // Kết nối database

// Kiểm tra nếu giỏ hàng rỗng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Tính tổng tiền
$totalPrice = 0;
foreach ($_SESSION['cart'] as $id => $item) {
    $itemPrice = isset($item['price']) ? $item['price'] : 0;
    $itemQuantity = isset($item['quantity']) ? $item['quantity'] : 1;
    $totalPrice += $itemPrice * $itemQuantity;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Thông Tin Đơn Hàng</h1>

        <!-- Form Đặt Hàng -->
        <form id="orderForm" class="needs-validation" novalidate>
            <div class="row">
                <!-- Thông Tin Người Nhận -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Thông Tin Người Nhận</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="fullname">Họ và Tên:</label>
                                <input type="text" id="fullname" name="fullname" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng nhập họ và tên!</div>
                            </div>

                            <div class="form-group">
                                <label for="address">Địa Chỉ:</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng nhập địa chỉ!</div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Số Điện Thoại:</label>
                                <input type="text" id="phone" name="phone" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng nhập số điện thoại!</div>
                            </div>

                            <div class="form-group">
                                <label for="total">Tổng Tiền:</label>
                                <input type="text" id="total" value="<?= number_format($totalPrice, 0, ',', '.'); ?> $"
                                    class="form-control" readonly>
                            </div>

                            <button type="submit" class="btn btn-success btn-block"
                                onclick="window.location.href='dathang.php';">
                                Đặt hàng
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chi Tiết Giỏ Hàng -->
                <div class=" col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h3 class="mb-0">Chi Tiết Giỏ Hàng</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Hình Ảnh</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Giá</th>
                                        <th>Số Lượng</th>
                                        <th>Thành Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): 
                                        $productImage = isset($item['image']) ? htmlspecialchars($item['image']) : 'default.jpg';
                                        $productName = isset($item['name']) ? htmlspecialchars($item['name']) : 'Sản phẩm';
                                        $productPrice = isset($item['price']) ? $item['price'] : 0;
                                        $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                                        $subtotal = $productPrice * $quantity;
                                    ?>
                                    <tr>
                                        <td><img src="<?= $productImage; ?>" alt="Sản phẩm" width="80"
                                                class="img-thumbnail"></td>
                                        <td><?= $productName; ?></td>
                                        <td><?= number_format($productPrice, 0, ',', '.'); ?> $</td>
                                        <td><?= $quantity; ?></td>
                                        <td><?= number_format($subtotal, 0, ',', '.'); ?> $</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="text-right font-weight-bold">
                                Tổng Tiền: <?= number_format($totalPrice, 0, ',', '.'); ?> $
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Ngừng việc gửi form ngay lập tức

        // Lấy thông tin từ form
        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'process_checkout.php', true);
        xhr.onload = function() {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = 'index.php'; // Chuyển về trang chủ
                } else {
                    alert(response.message || 'Có lỗi xảy ra khi đặt hàng!');
                }
            } catch (error) {
                console.error('Phản hồi không hợp lệ:', xhr.responseText);
                alert('Phản hồi từ máy chủ không hợp lệ! Kiểm tra console để biết thêm chi tiết.');
            }
        };
        xhr.send(formData);
    });
    </script>
</body>

</html>