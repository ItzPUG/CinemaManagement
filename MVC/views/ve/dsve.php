<?php
session_start();
ob_start(); 
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
    <title>Danh Sách Vé</title>
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
        <h1 class="text-center mb-4">Danh Sách Vé</h1>

        <div class="table-container">
            <?php if (count($tickets) > 0): ?>
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Mã Vé</th>
                            <th>Số Ghế</th>
                            <th>Trạng Thái</th>
                            <th>Mã Suất Chiếu</th>
                            <th>Mã Khách Hàng đã mua</th>
                            <th>Mã Phim</th>
                            <th style="width: 120px;">Cập Nhật</th>
                            <th style="width: 120px;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?php echo $ticket->MaVe; ?></td>
                                <td><?php echo $ticket->SoGhe; ?></td>
                                <td><?php echo $ticket->TrangThai; ?></td>
                                <td><?php echo $ticket->MaSuatChieu; ?></td>
                                <td><?php echo $ticket->MaKhachHang; ?></td>
                                <td><?php echo $ticket->MaPhim; ?></td>
                                <td>
                                    <a href="index.php?controllers=ve&action=capNhatVeActionForm&MaVe=<?php echo $ticket->MaVe; ?>" class="btn btn-update btn-sm">
                                        <i class="fas fa-edit"></i> Cập Nhật
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?controllers=ve&action=xoaVeAction&MaVe=<?php echo $ticket->MaVe; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa vé này?');">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Không có vé nào.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Thêm Bootstrap JS và jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

</body>
</html>
<?php $content = ob_get_clean(); ?>

<?php include 'views\layouts\application.php'; ?>