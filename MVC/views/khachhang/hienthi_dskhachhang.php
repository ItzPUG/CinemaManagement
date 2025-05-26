<?php ob_start(); 
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Chuyển hướng về trang đăng nhập
    header('Location: taikhoan/dangnhap.php?message=Bạn+chưa+đăng+nhập.');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Khách Hàng</title>
    <!-- Thêm Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Thêm Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 30px;
            max-width: 1250px;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 9px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
            white-space: nowrap;
        }
        .btn-update {
            background-color: #28a745;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-update:hover, .btn-delete:hover {
            color: white;
        }
        .message {
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center mb-4">Danh Sách Khách Hàng</h1>

        <div class="table-container">
            <?php if (count($customers) > 0): ?>
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Mã Khách Hàng</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Số Điện Thoại</th>
                            <th>Địa Chỉ</th>
                            <th>Ngày Đăng Ký</th>
                            <th style="width: 120px;">Cập Nhật</th>
                            <th style="width: 120px;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo $customer->MaKhachHang; ?></td>
                                <td><?php echo $customer->TenKhachHang; ?></td>
                                <td><?php echo $customer->Email; ?></td>
                                <td><?php echo $customer->SoDienThoai; ?></td>
                                <td><?php echo $customer->DiaChi; ?></td>
                                <td><?php echo $customer->NgayDangKy; ?></td>
                                <td>
                                    <a href="index.php?controllers=khachhang&action=capNhatKhachHangAction&MaKhachHang=<?php echo $customer->MaKhachHang; ?>" class="btn btn-update btn-sm">
                                        <i class="fas fa-edit"></i> Cập Nhật
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?controllers=khachhang&action=xoaKhachHangAction&MaKhachHang=<?php echo $customer->MaKhachHang; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Không có khách hàng nào.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Thêm Bootstrap JS và jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
</body>
</html>
<?php $content = ob_get_clean(); ?>

<?php include  __DIR__ . '/../layouts/application.php';?>