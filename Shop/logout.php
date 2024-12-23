<?php
session_start(); // Bắt đầu phiên làm việc

// Xóa tất cả dữ liệu trong session
session_unset();
session_destroy();

// Điều hướng về trang đăng nhập
header("Location: Login.php");
exit();