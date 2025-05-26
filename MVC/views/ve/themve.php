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
    $maVe = $_POST['MaVe'];
    $soGhe = $_POST['SoGhe'];
    $trangThai = $_POST['TrangThai'];
    $maSuatChieu = $_POST['MaSuatChieu'];
    $maKhachHang = $_POST['MaKhachHang'];
    $maPhim = $_POST['MaPhim'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($maVe) || empty($soGhe) || empty($trangThai) || empty($maSuatChieu) || empty($maKhachHang) || empty($maPhim)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Kết nối cơ sở dữ liệu và chèn dữ liệu vào bảng vé
        try {
            $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
            $query = "INSERT INTO ve (MaVe, SoGhe, TrangThai, MaSuatChieu, MaKhachHang, MaPhim) 
                      VALUES (:MaVe, :SoGhe, :TrangThai, :MaSuatChieu, :MaKhachHang, :MaPhim)";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':MaVe', $maVe);
            $stmt->bindParam(':SoGhe', $soGhe);
            $stmt->bindParam(':TrangThai', $trangThai);
            $stmt->bindParam(':MaSuatChieu', $maSuatChieu);
            $stmt->bindParam(':MaKhachHang', $maKhachHang);
            $stmt->bindParam(':MaPhim', $maPhim);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                $success_message = "Vé đã được thêm thành công!";
            } else {
                $error_message = "Có lỗi xảy ra khi thêm vé!";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi: Vé đã có sẵn." ; //$e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Vé</title>
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
        select {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%; /* Đảm bảo thẻ select chiếm hết chiều rộng của form */
        box-sizing: border-box; /* Đảm bảo padding không làm thẻ vượt quá chiều rộng */
        }
    </style>

<?php
// Bắt đầu buffer để lưu nội dung chính
ob_start();
?>
</head>
<body>
<div class="container">
    <h1>Thêm Vé</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form thêm vé -->
    <form action="" method="POST">
        <label for="MaVe">Mã Vé:</label>
        <input type="text" id="MaVe" name="MaVe" required>

        <label for="SoGhe">Số Ghế:</label>
        <input type="text" id="SoGhe" name="SoGhe" required>

        <label for="TrangThai">Trạng Thái:</label>
        <select id="TrangThai" name="TrangThai" required>
            <option value="Da Ban">Đã Bán</option>
            <option value="Trong">Còn</option>
        </select>
        <label for="MaSuatChieu">Mã Suất Chiếu:</label>
        <input type="text" id="MaSuatChieu" name="MaSuatChieu" required>

        <label for="MaKhachHang">Mã Khách Hàng:</label>
        <input type="text" id="MaKhachHang" name="MaKhachHang" required>

        <label for="MaPhim">Mã Phim:</label>
        <input type="text" id="MaPhim" name="MaPhim" required>

        <button type="submit">Thêm Vé</button>
    </form>
</div>

</body>
</html>
<?php
// Lưu nội dung vào biến $content
$content = ob_get_clean();

// Bao gồm layout
include 'views\layouts\application.php';
?>
