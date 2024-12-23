<?php
include __DIR__ . '/../config/db.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận dữ liệu từ biểu mẫu
    $Name_product = trim($_POST['Name_product'] ?? '');
    $Price_product = trim($_POST['Price_product'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($Name_product) || empty($Price_product)) {
        echo "Vui lòng nhập đầy đủ thông tin sản phẩm.";
        exit();
    }

    if (!is_numeric($Price_product) || $Price_product <= 0) {
        echo "Giá sản phẩm phải là số hợp lệ lớn hơn 0.";
        exit();
    }

    // Kiểm tra tệp hình ảnh
    if (isset($_FILES['Image_product']) && $_FILES['Image_product']['error'] === UPLOAD_ERR_OK) {
        $target_dir = 'image/'; // Đường dẫn thư mục lưu hình ảnh

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES['Image_product']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Kiểm tra định dạng tệp hình ảnh
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Chỉ cho phép các định dạng tệp hình ảnh: JPG, JPEG, PNG & GIF.";
            exit();
        }

        // Đường dẫn đầy đủ của file sau khi tải lên
        $target_file = $target_dir . basename($_FILES['Image_product']['name']);
        $image_path = $target_file; // Lưu đường dẫn ảnh để sử dụng khi chèn vào CSDL

        // Tải hình ảnh lên
        if (!move_uploaded_file($_FILES['Image_product']['tmp_name'], $target_file)) {
            echo "Có lỗi xảy ra khi tải hình ảnh.";
            exit();
        }
    } else {
        echo "Vui lòng tải lên hình ảnh sản phẩm.";
        exit();
    }

    // Chèn dữ liệu vào cơ sở dữ liệu
    $stmt = $connect->prepare("INSERT INTO admin_shop (Name_product, Price_product, Image_product) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $Name_product, $Price_product, $image_path); // s = string, d = double

    if ($stmt->execute()) {
        // Thêm sản phẩm thành công, điều hướng về dashboard.php
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close(); // Đóng prepared statement
    $connect->close(); // Đóng kết nối
}
?>