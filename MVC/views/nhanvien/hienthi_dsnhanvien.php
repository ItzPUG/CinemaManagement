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
    <title>Danh Sách Nhân Viên</title>
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
        <h1 class="text-center mb-4">Danh Sách Nhân Viên</h1>

        <div class="table-container">
            <?php
            // Kết nối đến cơ sở dữ liệu và lấy danh sách nhân viên
            try {
                $db = DB::getInstance(); // Kết nối CSDL qua lớp DB
                $query = "SELECT * FROM bophanbanve"; // Truy vấn lấy dữ liệu nhân viên
                $stmt = $db->query($query);
                $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                echo "<p class='error'>Lỗi: " . $e->getMessage() . "</p>";
                $employees = [];
            }
            ?>

            <?php if (count($employees) > 0): ?>
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Mã Nhân Viên</th>
                            <th>Tên Nhân Viên</th>
                            <th>Ca Làm Việc</th>
                            <th>Số Điện Thoại</th>
                            <th>Lương Cơ Bản</th>
                            <th>Chức Vụ</th>
                            <th style="width: 120px;">Cập Nhật</th>
                            <th style="width: 120px;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo $employee->MaNhanVien; ?></td>
                                <td><?php echo $employee->TenNhanVien; ?></td>
                                <td><?php echo $employee->CaLamViec; ?></td>
                                <td><?php echo $employee->SoDienThoai; ?></td>
                                <td><?php echo number_format($employee->LuongCoBan, 0, ',', '.'); ?> VND</td>
                                <td><?php echo $employee->ChucVu; ?></td>
                                <td>
                                    <a href="index.php?controllers=nhanvien&action=capNhatNhanVienAction&MaNhanVien=<?php echo $employee->MaNhanVien; ?>" class="btn btn-update btn-sm">
                                        <i class="fas fa-edit"></i> Cập Nhật
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?controllers=nhanvien&action=xoaNhanVienAction&MaNhanVien=<?php echo $employee->MaNhanVien; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Không có nhân viên nào.</p>
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