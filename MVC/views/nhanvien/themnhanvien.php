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
    $tenNhanVien = $_POST['TenNhanVien'];
    $caLamViec = $_POST['CaLamViec'];
    $soDienThoai = $_POST['SoDienThoai'];
    $luongCoBan = $_POST['LuongCoBan'];
    $chucVu = $_POST['ChucVu'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($tenNhanVien) || empty($caLamViec) || empty($soDienThoai) || empty($luongCoBan) || empty($chucVu)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($luongCoBan < 0) { // Kiểm tra số âm
        $error_message = "Lương cơ bản không được là số âm!";
    } else {
        // Kết nối cơ sở dữ liệu và chèn dữ liệu vào bảng nhân viên
        try {
            $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
            $query = "INSERT INTO bophanbanve (TenNhanVien, CaLamViec, SoDienThoai, LuongCoBan, ChucVu) 
                      VALUES (:TenNhanVien, :CaLamViec, :SoDienThoai, :LuongCoBan, :ChucVu)";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':TenNhanVien', $tenNhanVien);
            $stmt->bindParam(':CaLamViec', $caLamViec);
            $stmt->bindParam(':SoDienThoai', $soDienThoai);
            $stmt->bindParam(':LuongCoBan', $luongCoBan);
            $stmt->bindParam(':ChucVu', $chucVu);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                $success_message = "Nhân viên đã được thêm thành công!";
            } else {
                $error_message = "Có lỗi xảy ra khi thêm nhân viên!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi: Nhân viên đã tồn tại hoặc thông tin không hợp lệ.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên</title>
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
        input, textarea, select {
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
                const luongCoBan = parseFloat(document.getElementById('LuongCoBan').value);

                if (luongCoBan < 0) {
                    e.preventDefault();
                    alert('Lương cơ bản không được là số âm!');
                }
            });
        });
    </script>
</head>
<body>

<div class="container">
    <h1>Thêm Nhân Viên</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form thêm nhân viên -->
    <form action="" method="POST">
        <label for="TenNhanVien">Họ Tên:</label>
        <input type="text" id="TenNhanVien" name="TenNhanVien" required>

        <label for="CaLamViec">Ca Làm Việc:</label>
        <input type="text" id="CaLamViec" name="CaLamViec" required>

        <label for="SoDienThoai">Số Điện Thoại:</label>
        <input type="text" id="SoDienThoai" name="SoDienThoai" required>

        <label for="LuongCoBan">Lương Cơ Bản:</label>
        <input type="number" step="0.01" id="LuongCoBan" name="LuongCoBan" required>

        <label for="ChucVu">Chức Vụ:</label>
        <select id="ChucVu" name="ChucVu" required>
            <option value="Quan ly">Quản lý</option>
            <option value="Nhan vien ban ve">Nhân viên bán vé</option>
            <option value="Nhan vien kiem tra">Nhân viên kiểm tra</option>
            <option value="Nhan vien quet ve">Nhân viên quét vé</option>
        </select>

        <button type="submit">Thêm Nhân Viên</button>
    </form>
</div>

</body>
</html>
