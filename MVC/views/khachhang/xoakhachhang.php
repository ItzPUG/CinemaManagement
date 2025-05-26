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

// Kiểm tra nếu đã nhận được mã khách hàng qua URL
if (isset($_GET['MaKhachHang'])) {
    $maKhachHang = $_GET['MaKhachHang'];

    // Kiểm tra xem khách hàng có tồn tại trong cơ sở dữ liệu không
    try {
        $query = "SELECT * FROM khach_hang WHERE MaKhachHang = :MaKhachHang";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaKhachHang', $maKhachHang, PDO::PARAM_INT);
        $stmt->execute();
        $khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$khachHang) {
            // Nếu khách hàng không tồn tại
            echo "<script>
                    alert('Không tìm thấy khách hàng với mã $maKhachHang!');
                    window.location.href = 'index.php?controllers=khachhang&action=hienthi_dskhachhangAction';
                  </script>";
            exit;
        }

        // Nếu khách hàng tồn tại, tiến hành xóa
        $query = "DELETE FROM khach_hang WHERE MaKhachHang = :MaKhachHang";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaKhachHang', $maKhachHang, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Hiển thị thông báo thành công và chuyển hướng
            echo "<script>
                    alert('Khách hàng với mã $maKhachHang đã được xóa thành công!');
                    window.location.href = 'index.php?controllers=khachhang&action=hienthi_dskhachhangAction';
                  </script>";
            exit;
        } else {
            $error_message = "Có lỗi xảy ra khi xóa khách hàng.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
} else {
    $error_message = "Không có mã khách hàng được cung cấp.";
}

// Hiển thị lỗi nếu có
if ($error_message) {
    echo "<script>
            alert('$error_message');
            window.location.href = 'index.php?controllers=khachhang&action=hienThiDanhSach';
          </script>";
    exit;
}
?>
