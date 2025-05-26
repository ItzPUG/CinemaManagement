<?php
ob_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /MVC/views/taikhoan/dangnhap.php?message=Bạn+chưa+đăng+nhập.');
    exit;
}

require_once dirname(__DIR__, 2) . '/connection.php';

$error_message = '';
$success_message = isset($_GET['message']) ? $_GET['message'] : '';

try {
    $db = DB::getInstance();
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Lấy danh sách suất chiếu
try {
    $query = "SELECT MaSuatChieu, ThoiGianChieu, MaPhim FROM SuatChieu ORDER BY ThoiGianChieu";
    $stmt = $db->query($query);
    $suatChieus = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Lỗi: " . $e->getMessage();
    $suatChieus = [];
}

// Lấy danh sách ghế theo suất chiếu đã chọn
$selectedSuatChieu = isset($_POST['MaSuatChieu']) ? $_POST['MaSuatChieu'] : '';
$seats = [];

if (!empty($selectedSuatChieu)) {
    try {
        $query = "SELECT * FROM Ghe WHERE MaSuatChieu = :MaSuatChieu ORDER BY SoHang, SoCot";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaSuatChieu', $selectedSuatChieu);
        $stmt->execute();
        $seats = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
}

// Lấy danh sách khách hàng
try {
    $query = "SELECT MaKhachHang, TenKhachHang FROM khach_hang ORDER BY TenKhachHang";
    $stmt = $db->query($query);
    $khachHangs = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $error_message = "Lỗi: " . $e->getMessage();
    $khachHangs = [];
}

// Xử lý khi bấm vào ghế
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MaGhe'])) {
    $MaGhe = $_POST['MaGhe'];
    $MaKhachHang = $_POST['MaKhachHang'];

    try {
        // Kiểm tra trạng thái ghế
        $checkQuery = "SELECT TrangThai, MaKhachHang FROM Ghe WHERE MaGhe = :MaGhe";
        $stmt = $db->prepare($checkQuery);
        $stmt->bindParam(':MaGhe', $MaGhe);
        $stmt->execute();
        $seat = $stmt->fetch(PDO::FETCH_OBJ);

        if ($seat) {
            // Kiểm tra nếu ghế đã được đặt hoặc đang giữ bởi một khách hàng khác
            if (($seat->TrangThai == 'Da Dat' || $seat->TrangThai == 'Dang Giu') && $seat->MaKhachHang != $MaKhachHang) {
                $error_message = "Ghế này đã được đặt hoặc đang giữ bởi khách hàng khác.";
            } else {
                // Chuẩn bị tham số
                $params = [':MaGhe' => $MaGhe];

                if ($seat->TrangThai == 'Dang Giu') {
                    // Nếu ghế đang giữ -> Chuyển thành Đã đặt
                    $updateQuery = "UPDATE Ghe SET TrangThai = 'Da Dat', MaKhachHang = :MaKhachHang WHERE MaGhe = :MaGhe";
                    $params[':MaKhachHang'] = $MaKhachHang;
                } elseif ($seat->TrangThai == 'Da Dat') {
                    // Nếu ghế đã đặt -> Chuyển thành Trống
                    $updateQuery = "UPDATE Ghe SET TrangThai = 'Trống', MaKhachHang = NULL WHERE MaGhe = :MaGhe";
                } else {
                    // Nếu ghế trống -> Chuyển thành Đang giữ
                    $updateQuery = "UPDATE Ghe SET TrangThai = 'Dang Giu', MaKhachHang = :MaKhachHang WHERE MaGhe = :MaGhe";
                    $params[':MaKhachHang'] = $MaKhachHang;
                }

                $stmt = $db->prepare($updateQuery);
                $stmt->execute($params);

                header("Location: index.php?controllers=ghe&action=datGheAction&message=Đổi+trạng+thái+ghế+thành+công");
                exit;
            }
        } else {
            $error_message = "Không tìm thấy thông tin ghế.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Vé</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { margin-top: 30px; text-align: center; }
        .seat-layout { 
            display: grid; grid-template-columns: repeat(10, 1fr); 
            gap: 10px; justify-content: center; margin-top: 20px;
        }
        .seat { 
            width: 55px; height: 55px; display: flex; align-items: center; 
            justify-content: center; font-weight: bold; border-radius: 8px;
            cursor: pointer; border: 1px solid black; font-size: 14px;
            transition: 0.3s; 
        }
        .seat-available { background-color: #28a745; color: white; }
        .seat-available:hover { background-color: #218838; }
        .seat-holding { background-color: #ffc107; color: white; }
        .seat-booked { background-color: #dc3545; color: white; cursor: pointer; }
        .seat-label { font-size: 18px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="text-primary">Đặt Vé</h1>

        <!-- Hiển thị thông báo -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <label for="MaSuatChieu" class="font-weight-bold">Chọn Suất Chiếu:</label>
            <select name="MaSuatChieu" id="MaSuatChieu" class="form-control" onchange="this.form.submit()">
                <option value="">-- Chọn suất chiếu --</option>
                <?php foreach ($suatChieus as $sc): ?>
                    <option value="<?php echo $sc->MaSuatChieu; ?>" <?php echo $selectedSuatChieu == $sc->MaSuatChieu ? 'selected' : ''; ?>>
                        <?php echo $sc->ThoiGianChieu . " - " . $sc->MaPhim; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (!empty($selectedSuatChieu)): ?>
            <form method="POST" action="index.php?controllers=ghe&action=datGheAction">
                <div class="form-group">
                    <label for="MaKhachHang">Chọn Khách Hàng:</label>
                    <select name="MaKhachHang" class="form-control" required>
                        <option value="">-- Chọn khách hàng --</option>
                        <?php foreach ($khachHangs as $kh): ?>
                            <option value="<?php echo $kh->MaKhachHang; ?>"><?php echo $kh->TenKhachHang; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="seat-layout">
                    <?php foreach ($seats as $seat): ?>
                        <button type="submit" name="MaGhe" value="<?php echo $seat->MaGhe; ?>" 
                            class="seat <?php 
                                echo $seat->TrangThai == 'Da Dat' ? 'seat-booked' : 
                                     ($seat->TrangThai == 'Dang Giu' ? 'seat-holding' : 'seat-available'); 
                            ?>">
                            <?php echo $seat->MaGhe; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </form>
        <?php endif; ?>

        <div class="seat-label mt-4">
            <span class="badge badge-success">🟩 Trống</span>
            <span class="badge badge-warning">🟧 Đang giữ</span>
            <span class="badge badge-danger">🟥 Đã đặt</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
</body>
</html>
<?php $content = ob_get_clean(); ?>

<?php include 'C:/xampp/htdocs/MVC/views/layouts/application.php'; ?>