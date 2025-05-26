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

// Lấy thông tin nhân viên cần sửa (dựa vào MaNhanVien từ URL)
if (isset($_GET['MaNhanVien'])) {
    $maNhanVien = $_GET['MaNhanVien'];
    $query = "SELECT * FROM bophanbanve WHERE MaNhanVien = :MaNhanVien";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':MaNhanVien', $maNhanVien, PDO::PARAM_INT);
    $stmt->execute();
    $nhanVien = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra nếu không tìm thấy nhân viên
    if (!$nhanVien) {
        $error_message = "Không tìm thấy nhân viên với mã $maNhanVien.";
    }
} else {
    $error_message = "Không có mã nhân viên được cung cấp.";
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($nhanVien)) {
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
        // Cập nhật thông tin nhân viên
        try {
            $query = "UPDATE bophanbanve 
                      SET TenNhanVien = :TenNhanVien, 
                          CaLamViec = :CaLamViec, 
                          SoDienThoai = :SoDienThoai, 
                          LuongCoBan = :LuongCoBan, 
                          ChucVu = :ChucVu 
                      WHERE MaNhanVien = :MaNhanVien";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':TenNhanVien', $tenNhanVien);
            $stmt->bindParam(':CaLamViec', $caLamViec);
            $stmt->bindParam(':SoDienThoai', $soDienThoai);
            $stmt->bindParam(':LuongCoBan', $luongCoBan);
            $stmt->bindParam(':ChucVu', $chucVu);
            $stmt->bindParam(':MaNhanVien', $maNhanVien, PDO::PARAM_INT);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                echo "<script>
                        alert('Thông tin nhân viên đã được cập nhật thành công!');
                        window.location.href = 'index.php?controllers=nhanvien&action=hienthi_dsNhanVienAction';
                      </script>";
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật thông tin nhân viên!";
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
    <title>Cập Nhật Thông Tin Nhân Viên</title>
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
                const luongCoBan = document.getElementById('LuongCoBan').value;

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
    <h1>Sửa Nhân Viên</h1>

    <!-- Hiển thị thông báo lỗi -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($nhanVien)): ?>
        <!-- Form sửa nhân viên -->
        <form action="" method="POST">
            <label for="TenNhanVien">Họ Tên:</label>
            <input type="text" id="TenNhanVien" name="TenNhanVien" value="<?php echo $nhanVien['TenNhanVien']; ?>" required>

            <label for="CaLamViec">Ca Làm Việc:</label>
            <input type="text" id="CaLamViec" name="CaLamViec" value="<?php echo $nhanVien['CaLamViec']; ?>" required>

            <label for="SoDienThoai">Số Điện Thoại:</label>
            <input type="text" id="SoDienThoai" name="SoDienThoai" value="<?php echo $nhanVien['SoDienThoai']; ?>" required>

            <label for="LuongCoBan">Lương Cơ Bản:</label>
            <input type="text" id="LuongCoBan" name="LuongCoBan" value="<?php echo $nhanVien['LuongCoBan']; ?>" required>

            <label for="ChucVu">Chức Vụ:</label>
            <input type="text" id="ChucVu" name="ChucVu" value="<?php echo $nhanVien['ChucVu']; ?>" required>

            <button type="submit">Cập Nhật Nhân Viên</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
