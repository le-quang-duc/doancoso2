<?php
session_start();
include __DIR__ . '/../config/db.php'; // Kết nối cơ sở dữ liệu
$totalPrice = 0;




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoping Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #fff;
            /* Màu và độ rộng của viền */
        }

        /* Giảm kích thước hình ảnh trong giỏ hàng */
        .cart-product-image {
            width: 100px;
            /* Đặt chiều rộng cho hình ảnh */
            height: auto;
            /* Giữ tỷ lệ khung hình gốc */
            object-fit: cover;
            /* Cắt ảnh nếu cần để vừa với khung hình */
        }

        .table_head .column-1,
        .table_head .column-2,
        .table_head .column-3,

        .table_head .column-5,
        .table_head .column-6 {
            text-align: left;
        }

        .table_head .column-4 {
            text-align: center;
        }
    </style>
    <!--===============================================================================================-->
</head>

<body class="animsition">

    <!-- Header -->
    <header class="header-v4">
        <!-- Header desktop -->
        <div class="container-menu-desktop">
            <!-- Topbar -->

            <div class="wrap-menu-desktop">
                <nav class="limiter-menu-desktop container">

                    <!-- Logo desktop -->
                    <a href="#" class="logo">
                        <img src="images/icons/logo-01.png" alt="IMG-LOGO">
                    </a>

                    <!-- Menu desktop -->
                    <div class="menu-desktop">
                        <ul class="main-menu">
                            <li>
                                <a href="index.php">Trang chủ</a>
                            </li>

                            <li>
                                <a href="product.php">Sản phẩm</a>
                            </li>

                            <li class="label1 active-menu" data-label1="hot">
                                <a href="shoping-cart.php">Giỏ hàng</a>
                            </li>



                            <li>
                                <a href="contact.php">Liên hệ</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Icon header -->
                    <div class="wrap-icon-header flex-w flex-r-m" id="menu">
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                            <i class="zmdi zmdi-search"></i>
                        </div>

                        <div onclick="location.href='shoping-cart.php'"
                            class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                            id="notiCart" data-notify="0">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>

                        <!-- Kiểm tra xem người dùng đã đăng nhập hay chưa -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Nếu người dùng đã đăng nhập, hiển thị nút Logout -->

                            <span> <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                            <a href="logout.php" class="ml-4">Logout</a>
                        <?php else: ?>
                            <!-- Nếu chưa đăng nhập, hiển thị nút Đăng Nhập và Đăng Ký -->
                            <a href="Login.php" class="m-4">Đăng Nhập</a>
                            <a href="Register.php" class="ml-4">Đăng Ký</a>
                        <?php endif; ?>

                    </div>
                </nav>
            </div>
        </div>

        <!-- Header Mobile -->
        <div class="wrap-header-mobile">
            <!-- Logo moblie -->
            <div class="logo-mobile">
                <a href="dashboard.php"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
            </div>

            <!-- Icon header -->
            <div class="wrap-icon-header flex-w flex-r-m m-r-15">
                <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                    <i class="zmdi zmdi-search"></i>
                </div>

                <div onclick="location.href='shoping-cart.php'"
                    class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                    id="notiCartM" data-notify="0">
                    <i class="zmdi zmdi-shopping-cart"></i>
                </div>
                <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti"
                    data-notify="0">
                    <i class="zmdi zmdi-favorite-outline"></i>
                </a>
            </div>

            <!-- Button show menu -->
            <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </div>
        </div>


        <!-- Menu Mobile -->
        <div class="menu-mobile">

            <ul class="main-menu-m">
                <li>
                    <a href="index.php">Trang chủ</a>

                    <span class="arrow-main-menu-m">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </span>
                </li>

                <li>
                    <a href="product.php">Sản phẩm</a>
                </li>

                <li>
                    <a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Giỏ hàng</a>
                </li>




                <li>
                    <a href="contact.php">Liên hệ</a>
                </li>
            </ul>
        </div>

        <!-- Modal Search -->
        <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
            <div class="container-search-header">
                <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                    <img src="images/icons/icon-close2.png" alt="CLOSE">
                </button>

                <form class="wrap-search-header flex-w p-l-15">
                    <button class="flex-c-m trans-04">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                    <input class="plh3" type="text" name="search" placeholder="Search...">
                </form>
            </div>
        </div>
    </header>



    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <span class="stext-109 cl4">
                Shopping Cart
            </span>
        </div>
    </div>
    <!-- hienthisp tỏng gio hang  -->

    <form class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Sản Phẩm</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Giá</th>
                                    <th class="column-4">Số Lượng</th>
                                    <th class="column-5">Tổng Cộng</th>
                                    <th class="column-6">Xóa</th>
                                </tr>
                                <tbody id="showCart">
                                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                        <?php foreach ($_SESSION['cart'] as $id => $item):

                                            // Kiểm tra sự tồn tại của các giá trị trong giỏ hàng
                                            $productName = isset($item['name']) ? $item['name'] : 'Tên sản phẩm';
                                            $productPrice = isset($item['price']) ? $item['price'] : 0;
                                            $productImage = isset($item['image']) ? $item['image'] : 'default.jpg';
                                            $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                                            $subtotal = $productPrice * $quantity;
                                            $totalPrice += $subtotal; // Cộng dồn tổng tiền giỏ hàng
                                    
                                        endforeach;
                                        // Cộng dồn tổng tiền giỏ hàng
                                    

                                        ?>

                                        <tr id="row-<?php echo $id; ?>">
                                            <td>
                                                <img src="<?php echo htmlspecialchars($productImage); ?>" alt="IMG"
                                                    class="img-fluid" style="width: 80px; height: auto;">
                                            </td>
                                            <td><?php echo htmlspecialchars($productName); ?></td>
                                            <td><?php echo number_format($productPrice, 0, ',', '.'); ?> $</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1"
                                                        onclick="updateQuantity(<?php echo $id; ?>, -1)">-</button>
                                                    <input type="number" id="quantity-<?php echo $id; ?>"
                                                        value="<?php echo $quantity; ?>" min="1"
                                                        class="form-control text-center" style="width: 60px;" readonly>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1"
                                                        onclick="updateQuantity(<?php echo $id; ?>, 1)">+</button>
                                                </div>
                                            </td>
                                            <td id="subtotal-<?php echo $id; ?>">
                                                <?php echo number_format($subtotal, 0, ',', '.'); ?> $
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeFromCart(<?php echo $id; ?>)">Xóa</button>
                                            </td>
                                        </tr>

                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">Giỏ hàng của bạn hiện tại không có
                                                sản phẩm nào.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">Kho Hàng</h4>
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">Tổng cộng:</span>
                            </div>
                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2" id="totalCart">
                                    <?php echo number_format($totalPrice, 0, ',', '.'); ?> $ </span>
                            </div>
                        </div>
                        <button type="button"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer"
                            onclick="window.location.href='dathang.php';">
                            Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function updateQuantity(id, change) {
            var quantityInput = document.getElementById('quantity-' + id);
            var currentQuantity = parseInt(quantityInput.value);
            var newQuantity = currentQuantity + change;

            if (newQuantity < 1) newQuantity = 1; // Số lượng tối thiểu là 1
            quantityInput.value = newQuantity;

            // Gửi yêu cầu AJAX để cập nhật số lượng sản phẩm trong session
            $.ajax({
                url: 'update_cart.php',
                method: 'POST',
                data: {
                    action: 'update',
                    id: id,
                    quantity: newQuantity
                },
                success: function (response) {
                    // Phân tích phản hồi từ server
                    var data = JSON.parse(response);

                    // Cập nhật subtotal
                    document.getElementById('subtotal-' + id).innerText = data.subtotal + ' $';

                    // Cập nhật tổng tiền
                    document.getElementById('totalCart').innerText = data.total + ' $';
                },
                error: function () {
                    alert('Có lỗi xảy ra khi cập nhật số lượng.');
                }
            });
        }

        function removeFromCart(productId) {
            $.ajax({
                url: 'update_cart.php',
                method: 'POST',
                data: {
                    action: 'remove',
                    id: productId
                },
                success: function (response) {
                    // Xóa sản phẩm khỏi giao diện
                    $('#row-' + productId).remove();
                    // Cập nhật lại tổng giỏ hàng
                    updateTotalPrice();
                },
                error: function () {
                    alert('Có lỗi xảy ra khi xóa sản phẩm.');
                }
            });
        }
    </script>

    <!-- Footer -->
    <footer class="bg3 p-t-75 p-b-32">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Loại sản phẩm
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Nữ
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Nam
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Phụ kiện
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Help
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Track Order
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Returns
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Shipping
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                FAQs
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        GET IN TOUCH
                    </h4>

                    <p class="stext-107 cl7 size-201">
                        Đà Nẵng, Việt Nam
                    </p>

                    <div class="p-t-27">
                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <a href="https://www.facebook.com/profile.php?id=100042107122382"><i
                                    class="fa fa-facebook"></i></a>
                        </a>

                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <a href="https://www.instagram.com/quocmy1711?igsh=ZnVhYzBubTUzbDQ="> <i
                                    class="fa fa-instagram"></i></a>
                        </a>

                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <a href="https://www.pinterest.com/quangduc225tt/"><i class="fa fa-pinterest-p"></i></a>
                        </a>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Newsletter
                    </h4>

                    <form>
                        <div class="wrap-input1 w-full p-b-4">
                            <input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email"
                                placeholder="email@example.com">
                            <div class="focus-input1 trans-04"></div>
                        </div>

                        <div class="p-t-18">
                            <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-t-40">
                <div class="flex-c-m flex-w p-b-18">
                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-01.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-02.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-03.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-04.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-05.png" alt="ICON-PAY">
                    </a>
                </div>

                <p class="stext-107 cl6 txt-center">
                    Sản phẩm tạo ra năm &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                </p>
            </div>
        </div>
    </footer>


    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    <script src="codeJS/menu.js"></script>
    <script src="codeJS/cart.js"></script>

    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <script>
        $(".js-select2").each(function () {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        })
    </script>
    <!--===============================================================================================-->
    <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script>
        $('.js-pscroll').each(function () {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function () {
                ps.update();
            })
        });
    </script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>

</html>