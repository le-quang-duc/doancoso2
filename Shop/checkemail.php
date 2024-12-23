<?php
include "config/db.php";  // Kết nối cơ sở dữ liệu

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($connect, $_POST['email']);

    $query = "SELECT * FROM accounts WHERE email = '$email'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "exists";  // Email đã tồn tại
    } else {
        echo "valid";  // Email hợp lệ
    }
}
?>