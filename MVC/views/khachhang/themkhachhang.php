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

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tenKhachHang = $_POST['TenKhachHang'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];
    $ngayDangKy = $_POST['NgayDangKy'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($tenKhachHang) || empty($email) || empty($soDienThoai) || empty($diaChi) || empty($ngayDangKy)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($soDienThoai < 0) { // Kiểm tra số âm
        $error_message = "Số điện thoại không được là số âm!";
    } else {
        // Kết nối cơ sở dữ liệu và chèn dữ liệu vào bảng khách hàng
        try {
            $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
            $query = "INSERT INTO khach_hang (TenKhachHang, Email, SoDienThoai, DiaChi, NgayDangKy) 
                      VALUES (:TenKhachHang, :Email, :SoDienThoai, :DiaChi, :NgayDangKy)";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':TenKhachHang', $tenKhachHang);
            $stmt->bindParam(':Email', $email);
            $stmt->bindParam(':SoDienThoai', $soDienThoai);
            $stmt->bindParam(':DiaChi', $diaChi);
            $stmt->bindParam(':NgayDangKy', $ngayDangKy);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                $success_message = "Khách hàng đã được thêm thành công!";
            } else {
                $error_message = "Có lỗi xảy ra khi thêm khách hàng!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi: Khách hàng đã có sẵn."; //$e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Khách Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
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
    <script>
        // Kiểm tra phía client
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const soDienThoai = parseFloat(document.getElementById('SoDienThoai').value);

                if (soDienThoai < 0) {
                    e.preventDefault();
                    alert('Số điện thoại không được là số âm!');
                }
            });
        });
    </script>
</head>
<body>

<div class="container">
    <h1>Thêm Khách Hàng</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form thêm khách hàng -->
    <form action="" method="POST">
        <label for="TenKhachHang">Họ Tên:</label>
        <input type="text" id="TenKhachHang" name="TenKhachHang" required>

        <label for="Email">Email:</label>
        <input type="email" id="Email" name="Email" required>

        <label for="SoDienThoai">Số Điện Thoại:</label>
        <input type="number" id="SoDienThoai" name="SoDienThoai" required>

        <label for="DiaChi">Địa Chỉ:</label>
        <textarea id="DiaChi" name="DiaChi" rows="3" required></textarea>

        <label for="NgayDangKy">Ngày Đăng Ký:</label>
        <input type="date" id="NgayDangKy" name="NgayDangKy" required>

        <button type="submit">Thêm Khách Hàng</button>
    </form>
</div>

</body>
</html>
