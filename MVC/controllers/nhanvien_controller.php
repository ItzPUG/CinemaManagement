<?php
// controllers/NhanVienController.php
require_once('models/NhanVien.php');

class NhanVienController
{
    // Phương thức hiển thị danh sách nhân viên
    public function hienthi_dsNhanVienAction()
    {
        // Lấy danh sách nhân viên từ model
        $employees = NhanVien::allNhanVien();

        // Truyền dữ liệu vào view
        $this->render('hienthi_dsnhanvien', ['employees' => $employees]);
    }

    // Hàm render view
    public function render($view, $data)
    {
        // Truyền dữ liệu đến view
        extract($data); // Biến $data sẽ được phân giải thành các biến riêng lẻ
        include('views/nhanvien/' . $view . '.php');
    }

    // Phương thức hiển thị form thêm nhân viên
    public function hienthiThemNhanVienAction()
    {
        // Render form thêm nhân viên
        $this->render('themnhanvien', []);
    }

    // Phương thức hiển thị form cập nhật nhân viên
    public function capNhatNhanVienAction()
    {
        // Render form cập nhật nhân viên
        $this->render('capnhatnhanvien', []);
    }

    // Phương thức hiển thị form xóa nhân viên
    public function xoaNhanVienAction()
    {
        // Render form xóa nhân viên
        $this->render('xoanhanvien', []);
    }

    // Phương thức hiển thị form tìm kiếm nhân viên
    public function timKiemNhanVienAction()
    {
        // Render form tìm kiếm nhân viên
        $this->render('timkiemnhanvien', []);
    }
}
?>
