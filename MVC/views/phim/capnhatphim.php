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

// Lấy thông tin phim cần sửa (dựa vào MaPhim từ URL)
if (isset($_GET['MaPhim'])) {
    $maPhim = $_GET['MaPhim'];
    $query = "SELECT * FROM phim WHERE MaPhim = :MaPhim";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':MaPhim', $maPhim, PDO::PARAM_STR);
    $stmt->execute();
    $phim = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra nếu không tìm thấy phim
    if (!$phim) {
        $error_message = "Không tìm thấy phim với mã $maPhim.";
    }
} else {
    $error_message = "Không có mã phim được cung cấp.";
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($phim)) {
    // Lấy dữ liệu từ form
    $tenPhim = $_POST['TenPhim'];
    $theLoai = $_POST['TheLoai'];
    $daoDien = $_POST['DaoDien'];
    $moTa = $_POST['MoTa'];
    $thoiLuong = $_POST['ThoiLuong'];
    $namSanXuat = $_POST['NamSanXuat'];
    $ngonNgu = $_POST['NgonNgu'];
    $hinhAnh = $_FILES['HinhAnh']['name'];

    // Kiểm tra xem tất cả các trường đã được điền đầy đủ hay chưa
    if (empty($tenPhim) || empty($theLoai) || empty($daoDien) || empty($moTa) || empty($thoiLuong) || empty($namSanXuat) || empty($ngonNgu)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($thoiLuong < 0) { // Kiểm tra số âm cho ThoiLuong
        $error_message = "Thời lượng không được là số âm!";
    } elseif ($namSanXuat < 0) { // Kiểm tra số âm cho NamSanXuat
        $error_message = "Năm sản xuất không được là số âm!";
    } else {
        // Xử lý upload hình ảnh
        if (!empty($hinhAnh)) {
            $target_dir = "resources/images/";
            $target_file = $target_dir . basename($hinhAnh);
            move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $target_file);
        } else {
            $hinhAnh = $phim['HinhAnh'];
        }

        // Cập nhật thông tin phim
        try {
            $query = "UPDATE phim 
                      SET TenPhim = :TenPhim, 
                          TheLoai = :TheLoai, 
                          DaoDien = :DaoDien, 
                          MoTa = :MoTa, 
                          ThoiLuong = :ThoiLuong, 
                          NamSanXuat = :NamSanXuat, 
                          NgonNgu = :NgonNgu,
                          HinhAnh = :HinhAnh
                      WHERE MaPhim = :MaPhim";
            $stmt = $db->prepare($query);

            // Gán giá trị vào các tham số
            $stmt->bindParam(':TenPhim', $tenPhim);
            $stmt->bindParam(':TheLoai', $theLoai);
            $stmt->bindParam(':DaoDien', $daoDien);
            $stmt->bindParam(':MoTa', $moTa);
            $stmt->bindParam(':ThoiLuong', $thoiLuong, PDO::PARAM_INT);
            $stmt->bindParam(':NamSanXuat', $namSanXuat, PDO::PARAM_INT);
            $stmt->bindParam(':NgonNgu', $ngonNgu);
            $stmt->bindParam(':HinhAnh', $hinhAnh);
            $stmt->bindParam(':MaPhim', $maPhim, PDO::PARAM_STR);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                echo "<script>
                        alert('Thông tin phim đã được cập nhật thành công!');
                        window.location.href = 'index.php?controllers=phim&action=hienthi_dsphimAction';
                      </script>";
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật thông tin phim!";
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
    <title>Cập Nhật Thông Tin Phim</title>
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
    <h1>Cập Nhật Phim</h1>

    <!-- Hiển thị thông báo lỗi -->
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($phim)): ?>
        <!-- Form sửa phim -->
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="TenPhim">Tên Phim:</label>
            <input type="text" id="TenPhim" name="TenPhim" value="<?php echo $phim['TenPhim']; ?>" required>

            <label for="TheLoai">Thể Loại:</label>
            <input type="text" id="TheLoai" name="TheLoai" value="<?php echo $phim['TheLoai']; ?>" required>

            <label for="DaoDien">Đạo Diễn:</label>
            <input type="text" id="DaoDien" name="DaoDien" value="<?php echo $phim['DaoDien']; ?>" required>

            <label for="MoTa">Mô Tả:</label>
            <textarea id="MoTa" name="MoTa" rows="4" required><?php echo $phim['MoTa']; ?></textarea>

            <label for="ThoiLuong">Thời Lượng (phút):</label>
            <input type="number" id="ThoiLuong" name="ThoiLuong" value="<?php echo $phim['ThoiLuong']; ?>" required>

            <label for="NamSanXuat">Năm Sản Xuất:</label>
            <input type="number" id="NamSanXuat" name="NamSanXuat" value="<?php echo $phim['NamSanXuat']; ?>" required>

            <label for="NgonNgu">Ngôn Ngữ:</label>
            <input type="text" id="NgonNgu" name="NgonNgu" value="<?php echo $phim['NgonNgu']; ?>" required>

            <label for="HinhAnh">Hình Ảnh:</label>
            <input type="file" id="HinhAnh" name="HinhAnh" accept="image/*">
            <?php if (!empty($phim['HinhAnh'])): ?>
                <img src="resources/images/<?php echo $phim['HinhAnh']; ?>" alt="Hình ảnh phim" style="max-width: 200px; margin-top: 10px;">
            <?php endif; ?>

            <button type="submit">Cập Nhật Phim</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
