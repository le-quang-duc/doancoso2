<?php
include __DIR__ . '/../config/db.php'; // Kết nối cơ sở dữ liệu
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    body {
        background-color: #f8f9fa;
    }

    .tab-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .table thead {
        background-color: #343a40;
        color: white;
    }
    </style>

    <script>
    function logout() {
        localStorage.setItem("accountLogin", null);
        location.href = "dashboard.php";
    }

    function searchAccount() {
        const input = document.getElementById("searchAccount").value.toLowerCase();
        const rows = document.querySelectorAll("#accountList tr");

        rows.forEach(row => {
            const username = row.cells[1].textContent.toLowerCase();
            row.style.display = username.includes(input) ? "" : "none";
        });
    }
    </script>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">Admin Dashboard</h2>
        <a style="color: #003eff; float: right" onclick="logout()" class="ml-3">Đăng xuất</a>
        <br><br>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#account">Quản lý Đơn Hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#product">Quản lý sản phẩm</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div id="account" class="container tab-pane active"><br>
            <h1>Quản Lý Đơn Hàng</h1>
            <div class="form-group">
                <input id="searchOrder" oninput="searchOrder()" class="form-control"
                    placeholder="Tìm kiếm đơn hàng theo tên khách hàng" style="max-width: 300px;">
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody id="orderList">
                    <?php
                    include __DIR__ . '/../config/db.php'; // Đường dẫn đến db.php
                    try {
                        $stmt = $pdo->query("SELECT * FROM orders");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['fullname']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['total_price']}</td>
                            <td>" . date("d/m/Y H:i:s", strtotime($row['created_at'])) . "</td>
                            <td><a href='chitiet.php?id={$row['id']}' class='btn btn-info'>Chi tiết</a></td>
                            <td><a href='delete_order.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa đơn hàng này?\")'>Xóa</a></td>
                          </tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Lỗi: " . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Quản lý sản phẩm -->
        <div id="product" class="container tab-pane fade"><br>
            <h1>Quản Lý Sản Phẩm</h1>
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addProductModal">Thêm sản
                phẩm</button>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Giá</th>
                        <th>Chỉnh sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody id="productList">
                    <?php



                    $result = $connect->query("SELECT * FROM admin_shop");

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            
                            
                                    <td>{$row['id']}</td>
                                    <td>{$row['Name_product']}</td>
                                    <td><img src='{$row['Image_product']}' width='50' height='50'></td>
                                    <td>{$row['Price_product']}</td>
                                    <td><a href='#' class='btn btn-warning' data-toggle='modal' data-target='#editProductModal'
                                    data-id='{$row['id']}'
                                    data-name='{$row['Name_product']}'
                                    data-price='{$row['Price_product']}'
                                    data-image='{$row['Image_product']}'>Edit</a></td>
                                    <td><a href='Deleteproduct.php?id={$row['id']}' class='btn btn-danger'>Delete</a></td>
                                  </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Modal Thêm sản phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="Addproduct.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="Name_product">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="Name_product" name="Name_product" required>
                        </div>
                        <div class="form-group">
                            <label for="Image_product">Hình ảnh</label>
                            <input type="file" class="form-control" id="Image_product" name="Image_product" required>
                        </div>
                        <div class="form-group">
                            <label for="Price_product">Giá</label>
                            <input type="number" class="form-control" id="Price_product" name="Price_product" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa sản phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="Editproduct.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="form-group">
                            <label for="Name_product">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="Name_product" name="Name_product" required>
                        </div>
                        <div class="form-group">
                            <label for="Image_product">Hình ảnh</label>
                            <input type="file" class="form-control" id="Image_product" name="Image_product">
                            <small id="currentImage" class="form-text text-muted">Hình ảnh hiện tại: <img
                                    id="currentImagePreview" width="50" height="50"></small>
                        </div>
                        <div class="form-group">
                            <label for="Price_product">Giá sản phẩm</label>
                            <input type="number" class="form-control" id="Price_product" name="Price_product" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Khi modal "Edit" được mở
    $('#editProductModal').on('show.bs.modal', function(event) {
        // Lấy dữ liệu từ nút "Edit" và gán vào các trường trong modal
        var button = $(event.relatedTarget); // Nút "Edit"
        var id = button.data('id');
        var name = button.data('name');
        var price = button.data('price');
        var image = button.data('image');

        var modal = $(this);
        modal.find('#product_id').val(id);
        modal.find('#Name_product').val(name);
        modal.find('#Price_product').val(price);
        modal.find('#currentImagePreview').attr('src', image);
    });
    </script>

</body>

</html>