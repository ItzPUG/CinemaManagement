<?php
// Đảm bảo session được khởi tạo ngay đầu trang
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Chuyển hướng về trang đăng nhập
    header('Location: taikhoan/dangnhap.php?message=Bạn+chưa+đăng+nhập.');
    exit;
}

// Khởi tạo thông báo lỗi và thành công
$error_message = '';
$success_message = '';
$nhanViens = [];  // Biến lưu trữ kết quả tìm kiếm

// Kết nối cơ sở dữ liệu
try {
    $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $maNhanVien = $_POST['MaNhanVien'] ?? '';

    // Nếu Mã Nhân Viên không rỗng, thực hiện tìm kiếm
    if (!empty($maNhanVien)) {
        // Chuẩn bị câu lệnh SQL tìm kiếm
        $query = "SELECT * FROM bophanbanve WHERE MaNhanVien LIKE :MaNhanVien";
        try {
            $stmt = $db->prepare($query);
            // Sử dụng bindValue thay vì bindParam
            $stmt->bindValue(':MaNhanVien', "%$maNhanVien%", PDO::PARAM_STR);
            $stmt->execute();
            $nhanViens = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($nhanViens) > 0) {
                $success_message = "Tìm thấy " . count($nhanViens) . " kết quả.";
            } else {
                $error_message = "Không tìm thấy nhân viên nào khớp với tiêu chí.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi truy vấn: " . $e->getMessage();
        }

    } else {
        $error_message = "Vui lòng nhập Mã Nhân Viên để tìm kiếm.";
    }
}

ob_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Kiếm Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 30px;
        }
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #333;
        }
        .btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .btn-update {
            background-color: #28a745;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Tìm Kiếm Nhân Viên</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <form action="" method="POST">
        <label for="MaNhanVien">Mã Nhân Viên:</label>
        <input type="text" id="MaNhanVien" name="MaNhanVien" placeholder="Nhập mã nhân viên" value="<?php echo $maNhanVien ?? ''; ?>">

        <button type="submit">Tìm Kiếm</button>
    </form>

    <!-- Hiển thị kết quả tìm kiếm -->
    <?php if (isset($nhanViens) && count($nhanViens) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã Nhân Viên</th>
                    <th>Tên Nhân Viên</th>
                    <th>Ca Làm Việc</th>
                    <th>Số Điện Thoại</th>
                    <th>Lương Cơ Bản</th>
                    <th>Chức Vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nhanViens as $nhanVien): ?>
                    <tr>
                        <td><?php echo $nhanVien['MaNhanVien']; ?></td>
                        <td><?php echo $nhanVien['TenNhanVien']; ?></td>
                        <td><?php echo $nhanVien['CaLamViec']; ?></td>
                        <td><?php echo $nhanVien['SoDienThoai']; ?></td>
                        <td><?php echo $nhanVien['LuongCoBan']; ?></td>
                        <td><?php echo $nhanVien['ChucVu']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy nhân viên nào khớp với tiêu chí.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$content = ob_get_clean();
include 'views\layouts\application.php';
?>