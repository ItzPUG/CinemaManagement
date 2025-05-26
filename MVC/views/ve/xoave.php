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

// Kiểm tra nếu đã nhận được mã vé qua URL
if (isset($_GET['MaVe'])) {
    $maVe = $_GET['MaVe'];

    // Kiểm tra xem vé có tồn tại trong cơ sở dữ liệu không
    try {
        $query = "SELECT * FROM ve WHERE MaVe = :MaVe";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaVe', $maVe, PDO::PARAM_STR);
        $stmt->execute();
        $ve = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ve) {
            // Nếu vé không tồn tại
            echo "<script>
                    alert('Không tìm thấy vé với mã $maVe!');
                    window.location.href = 'index.php?controllers=ve&action=hienthi_dsveAction';
                  </script>";
            exit;
        }

        // Nếu vé tồn tại, tiến hành xóa
        $query = "DELETE FROM ve WHERE MaVe = :MaVe";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaVe', $maVe, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Hiển thị thông báo thành công và chuyển hướng
            echo "<script>
                    alert('Vé với mã $maVe đã được xóa thành công!');
                    window.location.href = 'index.php?controllers=ve&action=hienthi_dsveAction';
                  </script>";
            exit;
        } else {
            $error_message = "Có lỗi xảy ra khi xóa vé.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
} else {
    $error_message = "Không có mã vé được cung cấp.";
}

// Hiển thị lỗi nếu có
if ($error_message) {
    echo "<script>
            alert('$error_message');
            window.location.href = 'index.php?controllers=ve&action=hienthi_dsveAction';
          </script>";
    exit;
}
?>
