<?php

// Khởi tạo thông báo lỗi và thành công
$error_message = '';
$success_message = '';
$maPhim = '';

// Hàm để tạo mã phim mới
function generateMaPhim($db) {
    // Lấy mã phim lớn nhất hiện tại
    $query = "SELECT MaPhim FROM phim ORDER BY MaPhim DESC LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $lastMaPhim = $result['MaPhim'];
        $number = (int)substr($lastMaPhim, 2) + 1;
        return 'PH' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        return 'PH001';
    }
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tenPhim = $_POST['TenPhim'];
    $theLoai = $_POST['TheLoai'];
    $daoDien = $_POST['DaoDien'];
    $moTa = $_POST['MoTa'];
    $thoiLuong = $_POST['ThoiLuong'];
    $namSanXuat = $_POST['NamSanXuat'];
    $ngonNgu = $_POST['NgonNgu'];
    $hinhAnh = null;

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($tenPhim) || empty($theLoai) || empty($daoDien) || empty($moTa) || empty($thoiLuong) || empty($namSanXuat) || empty($ngonNgu)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($thoiLuong < 0) { // Kiểm tra số âm cho ThoiLuong
        $error_message = "Thời lượng không được là số âm!";
    } elseif ($namSanXuat < 0) { // Kiểm tra số âm cho NamSanXuat
        $error_message = "Năm sản xuất không được là số âm!";
    } else {
        // Xử lý upload hình ảnh
        if (!empty($_FILES['HinhAnh']['name'])) {
            $target_dir = "resources/images/";
            $target_file = $target_dir . basename($_FILES['HinhAnh']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra định dạng file
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                $error_message = "Chỉ chấp nhận các định dạng JPG, JPEG, PNG, GIF.";
            } elseif (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $target_file)) {
                $hinhAnh = $target_file; // Lưu đường dẫn ảnh vào cơ sở dữ liệu
            } else {
                $error_message = "Có lỗi xảy ra khi tải lên hình ảnh.";
            }
        }

        // Kết nối cơ sở dữ liệu và chèn dữ liệu vào bảng phim
        if (empty($error_message)) {
            try {
                $db = DB::getInstance(); // Kết nối cơ sở dữ liệu qua lớp DB
                $maPhim = generateMaPhim($db); // Tạo mã phim mới

                $query = "INSERT INTO phim (MaPhim, TenPhim, TheLoai, DaoDien, MoTa, ThoiLuong, NamSanXuat, NgonNgu, HinhAnh) 
                          VALUES (:MaPhim, :TenPhim, :TheLoai, :DaoDien, :MoTa, :ThoiLuong, :NamSanXuat, :NgonNgu, :HinhAnh)";
                $stmt = $db->prepare($query);

                // Gán giá trị vào các tham số
                $stmt->bindParam(':MaPhim', $maPhim);
                $stmt->bindParam(':TenPhim', $tenPhim);
                $stmt->bindParam(':TheLoai', $theLoai);
                $stmt->bindParam(':DaoDien', $daoDien);
                $stmt->bindParam(':MoTa', $moTa);
                $stmt->bindParam(':ThoiLuong', $thoiLuong);
                $stmt->bindParam(':NamSanXuat', $namSanXuat);
                $stmt->bindParam(':NgonNgu', $ngonNgu);
                $stmt->bindParam(':HinhAnh', $hinhAnh);

                // Thực thi câu lệnh SQL
                if ($stmt->execute()) {
                    $success_message = "Phim đã được thêm thành công! Mã phim: " . $maPhim;
                } else {
                    $error_message = "Có lỗi xảy ra khi thêm phim!";
                }
            } catch (PDOException $e) {
                $error_message = "Lỗi: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Phim</title>
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
                const thoiLuong = parseFloat(document.getElementById('ThoiLuong').value);
                const namSanXuat = parseInt(document.getElementById('NamSanXuat').value);

                if (thoiLuong < 0) {
                    e.preventDefault();
                    alert('Thời lượng không được là số âm!');
                }

                if (namSanXuat < 0) {
                    e.preventDefault();
                    alert('Năm sản xuất không được là số âm!');
                }
            });
        });
    </script>
</head>
<body>

<div class="container">
    <h1>Thêm Phim</h1>

    <!-- Hiển thị thông báo lỗi hoặc thành công -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form thêm phim -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="TenPhim">Tên Phim:</label>
        <input type="text" id="TenPhim" name="TenPhim" required>

        <label for="TheLoai">Thể Loại:</label>
        <input type="text" id="TheLoai" name="TheLoai" required>

        <label for="DaoDien">Đạo Diễn:</label>
        <input type="text" id="DaoDien" name="DaoDien" required>

        <label for="MoTa">Mô Tả:</label>
        <textarea id="MoTa" name="MoTa" rows="3" required></textarea>

        <label for="ThoiLuong">Thời Lượng (phút):</label>
        <input type="number" id="ThoiLuong" name="ThoiLuong" required>

        <label for="NamSanXuat">Năm Sản Xuất:</label>
        <input type="number" id="NamSanXuat" name="NamSanXuat" required>

        <label for="NgonNgu">Ngôn Ngữ:</label>
        <input type="text" id="NgonNgu" name="NgonNgu" required>

        <label for="HinhAnh">Hình Ảnh:</label>
        <input type="file" id="HinhAnh" name="HinhAnh" accept="image/*">

        <button type="submit">Thêm Phim</button>
    </form>
</div>

</body>
</html>
