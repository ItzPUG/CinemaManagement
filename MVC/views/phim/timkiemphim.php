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
$phims = [];  // Biến lưu trữ kết quả tìm kiếm

// Kết nối cơ sở dữ liệu
try {
    $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $maPhim = $_POST['MaPhim'] ?? '';

    // Nếu Mã Phim không rỗng, thực hiện tìm kiếm
    if (!empty($maPhim)) {
        // Chuẩn bị câu lệnh SQL tìm kiếm
        $query = "SELECT * FROM phim WHERE MaPhim LIKE :MaPhim";
        try {
            $stmt = $db->prepare($query);
            // Sử dụng bindValue thay vì bindParam
            $stmt->bindValue(':MaPhim', "%$maPhim%", PDO::PARAM_STR);
            $stmt->execute();
            $phims = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($phims) > 0) {
                $success_message = "Tìm thấy " . count($phims) . " kết quả.";
            } else {
                $error_message = "Không tìm thấy phim nào khớp với tiêu chí.";
            }
        } catch (PDOException $e) {
            $error_message = "Lỗi truy vấn: " . $e->getMessage();
        }

    } else {
        $error_message = "Vui lòng nhập Mã Phim để tìm kiếm.";
    }
}

ob_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Kiếm Phim</title>
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
        .img-thumbnail {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Tìm Kiếm Phim</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <form action="" method="POST">
        <label for="MaPhim">Mã Phim:</label>
        <input type="text" id="MaPhim" name="MaPhim" placeholder="Nhập mã phim" value="<?php echo $maPhim ?? ''; ?>">

        <button type="submit">Tìm Kiếm</button>
    </form>

    <!-- Hiển thị kết quả tìm kiếm -->
    <?php if (isset($phims) && count($phims) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã Phim</th>
                    <th>Tên Phim</th>
                    <th>Thể Loại</th>
                    <th>Đạo Diễn</th>
                    <th>Mô Tả</th>
                    <th>Thời Lượng</th>
                    <th>Năm Sản Xuất</th>
                    <th>Ngôn Ngữ</th>
                    <th>Hình Ảnh</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($phims as $phim): ?>
                    <tr>
                        <td><?php echo $phim['MaPhim']; ?></td>
                        <td><?php echo $phim['TenPhim']; ?></td>
                        <td><?php echo $phim['TheLoai']; ?></td>
                        <td><?php echo $phim['DaoDien']; ?></td>
                        <td><?php echo $phim['MoTa']; ?></td>
                        <td><?php echo $phim['ThoiLuong']; ?></td>
                        <td><?php echo $phim['NamSanXuat']; ?></td>
                        <td><?php echo $phim['NgonNgu']; ?></td>
                        <td>
                            <?php if (!empty($phim['HinhAnh'])): ?>
                                <img src="resources/images/<?php echo $phim['HinhAnh']; ?>" class="img-thumbnail" alt="Hình ảnh phim">
                            <?php else: ?>
                                <span>Không có hình</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy phim nào khớp với tiêu chí.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$content = ob_get_clean();
include 'views\layouts\application.php';
?>