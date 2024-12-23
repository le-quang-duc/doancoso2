<?php
include __DIR__ . '/../config/db.php'; // Đảm bảo đường dẫn chính xác đến db.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $Name_product = $_POST['Name_product'];
    $Price_product = $_POST['Price_product'];
    $Image_product = $_POST['Image_product']; // Giữ lại hình ảnh cũ nếu không thay đổi ảnh

    // Kiểm tra xem có ảnh mới không
    if (isset($_FILES['Image_product']) && $_FILES['Image_product']['error'] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/image/";
        $imageFileType = strtolower(pathinfo($_FILES['Image_product']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            $target_file = $target_dir . uniqid() . "." . $imageFileType;
            if (move_uploaded_file($_FILES['Image_product']['tmp_name'], $target_file)) {
                $Image_product = $target_file;
            }
        }
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    $stmt = $connect->prepare("UPDATE admin_shop SET Name_product = ?, Price_product = ?, Image_product = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $Name_product, $Price_product, $Image_product, $product_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php"); // Quay lại trang quản lý sản phẩm
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . $stmt->error;
    }

    $stmt->close();
}
?>