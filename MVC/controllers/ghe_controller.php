<?php
// controllers/GheController.php
session_start(); // Khởi tạo phiên làm việc

require_once('models/ghe.php');
require_once('models/suatchieu.php');

class GheController
{
    // Hiển thị giao diện đặt ghế
    public function datGheAction()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            // Chuyển hướng về trang đăng nhập
            header('Location: taikhoan/dangnhap.php?message=Bạn+chưa+đăng+nhập.');
            exit;
        }

        $error_message = '';
        $success_message = '';
        $suatChieus = [];
        $selectedSuatChieu = '';
        $seats = [];

        try {
            // Lấy danh sách suất chiếu
            $suatChieus = SuatChieu::getAllSuatChieu();

            // Lấy danh sách ghế theo suất chiếu đã chọn
            if (isset($_POST['MaSuatChieu'])) {
                $selectedSuatChieu = $_POST['MaSuatChieu'];
                $seats = Ghe::getGheBySuatChieu($selectedSuatChieu);
            }
        } catch (Exception $e) {
            $error_message = "Lỗi: " . $e->getMessage();
        }

        // Load view
        $this->render('datghe', [
            'suatChieus' => $suatChieus,
            'selectedSuatChieu' => $selectedSuatChieu,
            'seats' => $seats,
            'error_message' => $error_message,
            'success_message' => $success_message
        ]);
    }

    // Xử lý khi bấm vào ghế
    public function xuLyDatGheAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MaGhe'])) {
            $MaGhe = $_POST['MaGhe'];

            try {
                // Lấy trạng thái ghế hiện tại
                $seat = Ghe::getGheById($MaGhe);

                if ($seat) {
                    if ($seat->TrangThai == 'Dang Giu') {
                        Ghe::updateTrangThaiGhe($MaGhe, 'Da Dat');
                    } elseif ($seat->TrangThai == 'Da Dat') {
                        Ghe::updateTrangThaiGhe($MaGhe, 'Trống');
                    } else {
                        Ghe::updateTrangThaiGhe($MaGhe, 'Dang Giu');
                    }
                }
                header("Location: /MVC/controllers/ghe_controller.php?action=datGhe");
                exit;
            } catch (Exception $e) {
                $error_message = "Lỗi: " . $e->getMessage();
            }
        }
    }

    // Hàm render view
    public function render($view, $data = [])
    {
        extract($data);
        include('views/ghe/' . $view . '.php');
    }
}

// Xử lý yêu cầu từ URL
$action = isset($_GET['action']) ? $_GET['action'] : 'datGhe';
$controller = new GheController();

switch ($action) {
    case 'datGhe':
        $controller->datGheAction();
        break;
    case 'xuLyDatGhe':
        $controller->xuLyDatGheAction();
        break;
    default:
        $controller->datGheAction();
        break;
}
?>