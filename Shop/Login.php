<?php
session_start();

// Kết nối cơ sở dữ liệu
include __DIR__ . '/../config/db.php';

$usernameError = $passwordError = "";
$username = $password = "";

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Kiểm tra nếu có lỗi về tên đăng nhập và mật khẩu
    if (empty($username)) {
        $usernameError = "Vui lòng nhập tên đăng nhập";
    }
    if (empty($password)) {
        $passwordError = "Vui lòng nhập mật khẩu";
    }

    // Nếu không có lỗi, thực hiện kiểm tra thông tin đăng nhập
    if (empty($usernameError) && empty($passwordError)) {
        // Truy vấn cơ sở dữ liệu để kiểm tra tài khoản
        $sql = "SELECT * FROM account WHERE username = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Điều hướng đến trang chính hoặc trang dashboard
                header("Location: index.php");
                exit();
            } else {
                $passwordError = "Mật khẩu không đúng";
            }
        } else {
            $usernameError = "Tên đăng nhập không tồn tại";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                                    <form action="Login.php" method="post">
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">Đăng Nhập</span>
                                        </div>

                                        <h5 style="color: red" id="display">
                                            <?php
                                            // Hiển thị lỗi nếu có
                                            if ($usernameError) {
                                                echo $usernameError;
                                            } elseif ($passwordError) {
                                                echo $passwordError;
                                            }
                                            ?>
                                        </h5>

                                        <div class="form-outline mb-4">
                                            <input type="text" id="username" name="username"
                                                class="form-control form-control-lg" placeholder="Tên đăng nhập"
                                                value="<?php echo htmlspecialchars($username); ?>">
                                        </div>

                                        <div class="form-outline mb-4 position-relative">
                                            <input type="password" id="password" name="password"
                                                class="form-control form-control-lg" placeholder="Mật khẩu">
                                            <span onclick="togglePassword()" class="position-absolute"
                                                style="right: 15px; top: 10px; cursor: pointer;">
                                                <i id="eye-icon" class="fas fa-eye"></i>
                                            </span>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit"
                                                name="submit">Đăng Nhập</button>
                                        </div>

                                        <a href="Register.php" style="color: #393f81;">
                                            <p class="mb-5 pb-lg-2"
                                                style="color: #393f81; text-align: center; font-size: 20px;">Bạn chưa có
                                                tài khoản?</p>
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

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>