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

// Lấy thông tin vé cần sửa (dựa vào MaVe từ URL)
if (isset($_GET['MaVe'])) {
    $maVe = $_GET['MaVe'];
    $query = "SELECT * FROM ve WHERE MaVe = :MaVe";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':MaVe', $maVe, PDO::PARAM_STR);
    $stmt->execute();
    $ve = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra nếu không tìm thấy vé
    if (!$ve) {
        $error_message = "Không tìm thấy vé với mã $maVe.";
    }
} else {
    $error_message = "Không có mã vé được cung cấp.";
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($ve)) {
    // Lấy dữ liệu từ form
    $soGhe = $_POST['SoGhe'];
    $trangThai = $_POST['TrangThai'];
    $maSuatChieu = $_POST['MaSuatChieu'];
    $maKhachHang = $_POST['MaKhachHang'];
    $maPhim = $_POST['MaPhim'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($soGhe) || empty($trangThai) || empty($maSuatChieu) || empty($maKhachHang) || empty($maPhim)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Cập nhật thông tin vé
        try {
            $query = "UPDATE ve 
                      SET SoGhe = :SoGhe, 
                          TrangThai = :TrangThai, 
                          MaSuatChieu = :MaSuatChieu, 
                          MaKhachHang = :MaKhachHang, 
                          MaPhim = :MaPhim
                      WHERE MaVe = :MaVe";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':SoGhe', $soGhe);
            $stmt->bindParam(':TrangThai', $trangThai);
            $stmt->bindParam(':MaSuatChieu', $maSuatChieu);
            $stmt->bindParam(':MaKhachHang', $maKhachHang);
            $stmt->bindParam(':MaPhim', $maPhim);
            $stmt->bindParam(':MaVe', $maVe, PDO::PARAM_STR);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                echo "<script>
                        alert('Thông tin vé đã được cập nhật thành công!');
                        window.location.href = 'index.php?controllers=ve&action=hienthi_dsveAction';
                      </script>";
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật thông tin vé!";
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
    <title>Cập Nhật Thông Tin Vé</title>
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
        input, select {
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
</head>
<body>

<div class="container">
    <h1>Cập nhật Vé</h1>

    <!-- Hiển thị thông báo lỗi -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($ve)): ?>
        <!-- Form sửa vé -->
        <form action="" method="POST">
            <label for="SoGhe">Số Ghế:</label>
            <input type="text" id="SoGhe" name="SoGhe" value="<?php echo isset($ve['SoGhe']) ? $ve['SoGhe'] : ''; ?>" required>

            <label for="TrangThai">Trạng Thái:</label>
            <select id="TrangThai" name="TrangThai" required>
                <option value="Da Ban" <?php echo (isset($ve['TrangThai']) && $ve['TrangThai'] == 'Da Ban') ? 'selected' : ''; ?>>Đã Bán</option>
                <option value="Trong" <?php echo (isset($ve['TrangThai']) && $ve['TrangThai'] == 'Trong') ? 'selected' : ''; ?>>Trống</option>
            </select>

            <label for="MaSuatChieu">Mã Suất Chiếu:</label>
            <input type="text" id="MaSuatChieu" name="MaSuatChieu" value="<?php echo isset($ve['MaSuatChieu']) ? $ve['MaSuatChieu'] : ''; ?>" required>

            <label for="MaKhachHang">Mã Khách Hàng:</label>
            <input type="number" id="MaKhachHang" name="MaKhachHang" value="<?php echo isset($ve['MaKhachHang']) ? $ve['MaKhachHang'] : ''; ?>" required>

            <label for="MaPhim">Mã Phim:</label>
            <input type="text" id="MaPhim" name="MaPhim" value="<?php echo isset($ve['MaPhim']) ? $ve['MaPhim'] : ''; ?>" required>

            <button type="submit">Cập Nhật Vé</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
