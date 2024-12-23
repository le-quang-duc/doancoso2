<?php

// Kết nối cơ sở dữ liệu
include __DIR__ . '/../config/db.php'; // Kết nối đến cơ sở dữ liệu

$UsernameError = $emailError = $passwordError = $rePasswordError = "";
$Username = $email = $password = $re_password = "";

if (isset($_POST["submit"])) {
    $Username = $_POST["Username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $re_password = $_POST["re_password"];

    // Mảng lưu lỗi
    $error = [];

    // Kiểm tra các trường bắt buộc
    if (empty($Username)) {
        $UsernameError = "Vui lòng nhập tên đăng nhập";
    }
    if (empty($email)) {
        $emailError = "Vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Email không hợp lệ";
    }
    if (empty($password)) {
        $passwordError = "Vui lòng nhập mật khẩu";
    }
    if (empty($re_password)) {
        $rePasswordError = "Vui lòng xác nhận mật khẩu";
    } elseif ($password !== $re_password) {
        $rePasswordError = "Mật khẩu không khớp";
    }

    // Kiểm tra nếu không có lỗi thì hash mật khẩu và lưu vào cơ sở dữ liệu
    if (empty($UsernameError) && empty($emailError) && empty($passwordError) && empty($rePasswordError)) {
        // Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $sql_check_username = "SELECT * FROM account WHERE username = ?";
        $stmt_check_username = $connect->prepare($sql_check_username);
        $stmt_check_username->bind_param("s", $Username);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();

        if ($result_check_username->num_rows > 0) {
            // Nếu tên đăng nhập đã tồn tại
            $UsernameError = "Tên đăng nhập đã tồn tại";
        } else {
            // Nếu không có lỗi, hash mật khẩu và thực hiện INSERT
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

            // Thực hiện câu truy vấn để lưu tài khoản vào cơ sở dữ liệu
            $sql = "INSERT INTO account (username, email, password) VALUES ('$Username', '$email', '$passwordHashed')";

            if ($connect->query($sql) === TRUE) {
                // Nếu lưu thành công, chuyển hướng đến trang đăng nhập
                header("Location: Login.php");
                exit();
            } else {
                // Nếu có lỗi khi lưu
                echo "Lỗi: " . $sql . "<br>" . $connect->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    /* Thêm khoảng cách cho các thông báo lỗi */
    .error-message {
        font-size: 0.9rem;
        color: #e74c3c;
        position: absolute;
        bottom: -20px;
        /* Đặt lỗi dưới trường nhập */
        left: 0;
        width: 100%;
    }

    .form-group {
        position: relative;
        /* Để các thông báo lỗi không làm thay đổi bố cục */
    }
    </style>
</head>

<body>
    <section class="vh-100" style="background-color: #9A616D;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="../Shop/images/img1.webp" alt="login form" class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form action="Register.php" method="post">
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <span class="h1 fw-bold mb-0">Đăng Ký Tài Khoản</span>
                                        </div>

                                        <!-- Tên đăng nhập (Thay đổi fullName thành username) -->
                                        <div class="form-group mb-3">
                                            <input type="text" id="Username" name="Username" class="form-control"
                                                placeholder="Nhập tên đăng nhập"
                                                value="<?php echo htmlspecialchars($Username); ?>" />
                                            <?php if ($UsernameError): ?>
                                            <div cass="error-message"><?php echo $UsernameError; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group mb-3">
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Nhập Email"
                                                value="<?php echo htmlspecialchars($email); ?>" />
                                            <?php if ($emailError): ?>
                                            <div class="error-message"><?php echo $emailError; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Mật khẩu -->
                                        <div class="form-group mb-3">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Nhập mật khẩu" />
                                            <?php if ($passwordError): ?>
                                            <div class="error-message"><?php echo $passwordError; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Xác nhận mật khẩu -->
                                        <div class="form-group mb-3">
                                            <input type="password" id="re_password" name="re_password"
                                                class="form-control" placeholder="Nhập lại mật khẩu" />
                                            <?php if ($rePasswordError): ?>
                                            <div class="error-message"><?php echo $rePasswordError; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit"
                                                name="submit">Đăng Ký</button>
                                        </div>

                                        <a href="Login.php" style="color: #393f81;">
                                            <p class="mb-5 pb-lg-2 text-center" style="font-size: 20px;">Bạn đã có tài
                                                khoản ư?</p>
                                        </a>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>