<?php
session_start();
include __DIR__ . '/../config/db.php'; // Đảm bảo đường dẫn đúng đến db.php

// Kiểm tra nếu có yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $product_id = $_POST['product_id'];
    $Name_product = $_POST['Name_product'];
    $Price_product = $_POST['Price_product'];
    $Image_product = $_POST['Image_product']; // Giữ lại hình ảnh cũ nếu không thay đổi ảnh

    // Kiểm tra xem có ảnh mới không
    if (isset($_FILES['Image_product']) && $_FILES['Image_product']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "image/";
        $imageFileType = strtolower(pathinfo($_FILES['Image_product']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Kiểm tra loại ảnh hợp lệ
        if (in_array($imageFileType, $allowed_types)) {
            $target_file = $target_dir . uniqid() . "." . $imageFileType;

            // Di chuyển ảnh đã upload vào thư mục
            if (move_uploaded_file($_FILES['Image_product']['tmp_name'], $target_file)) {
                // Cập nhật đường dẫn ảnh mới
                $Image_product = $target_file;
            } else {
                echo "Lỗi khi tải ảnh lên.";
            }
        } else {
            echo "Loại ảnh không hợp lệ.";
        }
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    $stmt = $connect->prepare("UPDATE admin_shop SET Name_product = ?, Price_product = ?, Image_product = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $Name_product, $Price_product, $Image_product, $product_id);

    if ($stmt->execute()) {
        // Nếu cập nhật thành công, chuyển hướng về trang quản lý sản phẩm
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . $stmt->error;
    }

    // Đóng statement
    $stmt->close();
}
?>