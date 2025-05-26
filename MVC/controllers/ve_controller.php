<?php
// controllers/VeController.php
require_once('models/ve.php');

class VeController
{
    // Phương thức hiển thị danh sách vé
    public function hienthi_dsveAction()
    {
        // Lấy danh sách vé từ model
        $tickets = Ve::allVe();

        // Truyền dữ liệu vào view
        $this->render('dsve', ['tickets' => $tickets]);
    }

    // Phương thức hiển thị form thêm vé
    public function hienthiThemVeAction()
    {
        // Render form thêm vé
        $this->render('themve', []);
    }

    // Phương thức hiển thị form cập nhật vé
    public function capNhatVeActionForm()
    {
        // Lấy thông tin vé cần cập nhật từ model
        $ticket = Ve::findVe($_GET['MaVe']); // Giả sử mã vé truyền qua URL

        // Render form cập nhật vé với dữ liệu hiện tại
        $this->render('capnhatve', ['ticket' => $ticket]);
    }

    // Phương thức xử lý cập nhật vé
    public function capNhatVeActionHandler()
    {
        // Kiểm tra dữ liệu POST và cập nhật vé
        if (isset($_POST['MaVe'])) {
            $ticket = new Ve($_POST['MaVe'], $_POST['SoGhe'], $_POST['TrangThai'], $_POST['MaSuatChieu'], $_POST['MaKhachHang'], $_POST['MaPhim']);
            $ticket->updateVe($_POST); // Gọi phương thức cập nhật vé
            header('Location: index.php?controllers=ve&action=hienthi_dsveAction');
        }
    }

    // Phương thức hiển thị form xóa vé
    public function xoaVeAction()
    {
        // Lấy thông tin vé cần xóa
        $ticket = Ve::findVe($_GET['MaVe']); // Giả sử mã vé truyền qua URL

        // Render form xóa vé với dữ liệu hiện tại
        $this->render('xoave', ['ticket' => $ticket]);
    }

    // Phương thức xử lý xóa vé
    public function xoaVeActionHandler()
    {
        // Kiểm tra dữ liệu GET và xóa vé
        if (isset($_GET['MaVe'])) {
            Ve::deleteVe($_GET['MaVe']); // Gọi phương thức xóa vé
            header('Location: index.php?controllers=ve&action=hienthi_dsveAction');
        }
    }

    // Phương thức hiển thị form tìm kiếm vé
    public function timKiemVeAction()
    {
        // Render form tìm kiếm vé
        $this->render('timkiemve', []);
    }

    // Hàm render view
    public function render($view, $data)
    {
        // Truyền dữ liệu đến view
        extract($data); // Biến $data sẽ được phân giải thành các biến riêng lẻ
        include('views/ve/' . $view . '.php');
    }
}
?>
