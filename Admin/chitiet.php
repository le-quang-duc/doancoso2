<?php
session_start();
include __DIR__ . '/../config/db.php'; // Kết nối cơ sở dữ liệu

$orderId = $_GET['id'];

// Lấy thông tin đơn hàng từ bảng orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :orderId");
$stmt->execute(['orderId' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Đơn hàng không tồn tại!";
    exit;
}

// Lấy thông tin sản phẩm trong đơn hàng từ bảng order_items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :orderId");
$stmt->execute(['orderId' => $orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Áp dụng margin-top 10px cho tất cả màn hình */
        .btn-primary {
            margin-top: 10px;
        }

        /* Đối với màn hình nhỏ, giảm khoảng cách margin-top */
        @media (max-width: 768px) {
            .btn-primary {
                margin-top: 5px;
                /* Khoảng cách nhỏ hơn trên màn hình nhỏ */
            }
        }

        /* Đối với màn hình cực nhỏ (di động) */
        @media (max-width: 576px) {
            .btn-primary {
                margin-top: 3px;
                /* Khoảng cách nhỏ hơn nữa */
            }
        }
    </style>
</head>

<body>
    <a href="dashboard.php" class="btn btn-primary" style="margin-top: 10px;">Quay Lại</a>
    <div class="container my-5">
        <h1 class="text-center mb-4">Chi Tiết Đơn Hàng</h1>

        <!-- Thông Tin Đơn Hàng -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Thông Tin Đơn Hàng</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="orderId">Mã Đơn Hàng:</label>
                            <input type="text" id="orderId" value="<?= $order['id']; ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="fullname">Tên Khách Hàng:</label>
                            <input type="text" id="fullname" value="<?= $order['fullname']; ?>" class="form-control"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa Chỉ:</label>
                            <input type="text" id="address" value="<?= $order['address']; ?>" class="form-control"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số Điện Thoại:</label>
                            <input type="text" id="phone" value="<?= $order['phone']; ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="total">Tổng Tiền:</label>
                            <input type="text" id="total"
                                value="<?= number_format($order['total_price'], 0, ',', '.'); ?> $" class="form-control"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="createdAt">Ngày Đặt Hàng:</label>
                            <input type="text" id="createdAt"
                                value="<?= date("d/m/Y H:i:s", strtotime($order['created_at'])); ?>"
                                class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi Tiết Các Sản Phẩm -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h3 class="mb-0">Các Sản Phẩm Trong Đơn Hàng</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
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
                            Tổng Tiền: <?= number_format($order['total_price'], 0, ',', '.'); ?> $
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>