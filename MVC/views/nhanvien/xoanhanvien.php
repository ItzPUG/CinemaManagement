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

// Kiểm tra nếu đã nhận được mã nhân viên qua URL
if (isset($_GET['MaNhanVien'])) {
    $maNhanVien = $_GET['MaNhanVien'];

    // Kiểm tra xem nhân viên có tồn tại trong cơ sở dữ liệu không
    try {
        $query = "SELECT * FROM bophanbanve WHERE MaNhanVien = :MaNhanVien";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaNhanVien', $maNhanVien, PDO::PARAM_INT);
        $stmt->execute();
        $nhanVien = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nhanVien) {
            // Nếu nhân viên không tồn tại
            echo "<script>
                    alert('Không tìm thấy nhân viên với mã $maNhanVien!');
                    window.location.href = 'index.php?controllers=nhanvien&action=hienthi_dsNhanVienAction';
                  </script>";
            exit;
        }

        // Nếu nhân viên tồn tại, tiến hành xóa
        $query = "DELETE FROM bophanbanve WHERE MaNhanVien = :MaNhanVien";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaNhanVien', $maNhanVien, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Hiển thị thông báo thành công và chuyển hướng
            echo "<script>
                    alert('Nhân viên với mã $maNhanVien đã được xóa thành công!');
                    window.location.href = 'index.php?controllers=nhanvien&action=hienthi_dsNhanVienAction';
                  </script>";
            exit;
        } else {
            $error_message = "Có lỗi xảy ra khi xóa nhân viên.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
} else {
    $error_message = "Không có mã nhân viên được cung cấp.";
}

// Hiển thị lỗi nếu có
if ($error_message) {
    echo "<script>
            alert('$error_message');
            window.location.href = 'index.php?controllers=nhanvien&action=hienthi_dsNhanVienAction';
          </script>";
    exit;
}
?>