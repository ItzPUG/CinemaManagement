<?php

// Khởi tạo thông báo lỗi và thành công
$error_message = '';
$success_message = '';

// Kết nối cơ sở dữ liệu
try {
    $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Lấy thông tin khách hàng cần sửa (dựa vào MaKhachHang từ URL)
if (isset($_GET['MaKhachHang'])) {
    $maKhachHang = $_GET['MaKhachHang'];
    $query = "SELECT * FROM khach_hang WHERE MaKhachHang = :MaKhachHang";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':MaKhachHang', $maKhachHang, PDO::PARAM_INT);
    $stmt->execute();
    $khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra nếu không tìm thấy khách hàng
    if (!$khachHang) {
        $error_message = "Không tìm thấy khách hàng với mã $maKhachHang.";
    }
} else {
    $error_message = "Không có mã khách hàng được cung cấp.";
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($khachHang)) {
    // Lấy dữ liệu từ form
    $tenKhachHang = $_POST['TenKhachHang'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];
    $ngayDangKy = $_POST['NgayDangKy'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($tenKhachHang) || empty($email) || empty($soDienThoai) || empty($diaChi) || empty($ngayDangKy)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($soDienThoai < 0) { // Kiểm tra số âm phía server
        $error_message = "Số điện thoại không được là số âm!";
    } else {
        // Cập nhật thông tin khách hàng
        try {
            $query = "UPDATE khach_hang 
                      SET TenKhachHang = :TenKhachHang, 
                          Email = :Email, 
                          SoDienThoai = :SoDienThoai, 
                          DiaChi = :DiaChi, 
                          NgayDangKy = :NgayDangKy 
                      WHERE MaKhachHang = :MaKhachHang";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':TenKhachHang', $tenKhachHang);
            $stmt->bindParam(':Email', $email);
            $stmt->bindParam(':SoDienThoai', $soDienThoai);
            $stmt->bindParam(':DiaChi', $diaChi);
            $stmt->bindParam(':NgayDangKy', $ngayDangKy);
            $stmt->bindParam(':MaKhachHang', $maKhachHang, PDO::PARAM_INT);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                echo "<script>
                        alert('Thông tin khách hàng đã được cập nhật thành công!');
                        window.location.href = 'index.php?controllers=khachhang&action=hienthi_dskhachhangAction';
                      </script>";
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật thông tin khách hàng!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Thông Tin Khách Hàng</title>
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
    <h1>Sửa Khách Hàng</h1>

    <!-- Hiển thị thông báo lỗi -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($khachHang)): ?>
        <!-- Form sửa khách hàng -->
        <form action="" method="POST">
            <label for="TenKhachHang">Họ Tên:</label>
            <input type="text" id="TenKhachHang" name="TenKhachHang" value="<?php echo $khachHang['TenKhachHang']; ?>" required>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" value="<?php echo $khachHang['Email']; ?>" required>

            <label for="SoDienThoai">Số Điện Thoại:</label>
            <input type="number" id="SoDienThoai" name="SoDienThoai" value="<?php echo $khachHang['SoDienThoai']; ?>" required>

            <label for="DiaChi">Địa Chỉ:</label>
            <textarea id="DiaChi" name="DiaChi" rows="3" required><?php echo $khachHang['DiaChi']; ?></textarea>

            <label for="NgayDangKy">Ngày Đăng Ký:</label>
            <input type="date" id="NgayDangKy" name="NgayDangKy" value="<?php echo $khachHang['NgayDangKy']; ?>" required>

            <button type="submit">Cập Nhật Khách Hàng</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
