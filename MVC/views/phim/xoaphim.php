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

// Kiểm tra nếu đã nhận được mã phim qua URL
if (isset($_GET['MaPhim'])) {
    $maPhim = $_GET['MaPhim'];

    // Kiểm tra xem phim có tồn tại trong cơ sở dữ liệu không
    try {
        $query = "SELECT * FROM phim WHERE MaPhim = :MaPhim";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaPhim', $maPhim, PDO::PARAM_INT);
        $stmt->execute();
        $phim = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$phim) {
            // Nếu phim không tồn tại
            echo "<script>
                    alert('Không tìm thấy phim với mã $maPhim!');
                    window.location.href = 'index.php?controllers=phim&action=hienthi_dsphimAction';
                  </script>";
            exit;
        }

        // Nếu phim tồn tại, tiến hành xóa
        $query = "DELETE FROM phim WHERE MaPhim = :MaPhim";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaPhim', $maPhim, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Hiển thị thông báo thành công và chuyển hướng
            echo "<script>
                    alert('Phim với mã $maPhim đã được xóa thành công!');
                    window.location.href = 'index.php?controllers=phim&action=hienthi_dsphimAction';
                  </script>";
            exit;
        } else {
            $error_message = "Có lỗi xảy ra khi xóa phim.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
} else {
    $error_message = "Không có mã phim được cung cấp.";
}

// Hiển thị lỗi nếu có
if ($error_message) {
    echo "<script>
            alert('$error_message');
            window.location.href = 'index.php?controllers=phim&action=hienthi_dsphimAction';
          </script>";
    exit;
}
?>
