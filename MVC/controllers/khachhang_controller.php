<?php
// controllers/KhachHangController.php
require_once('models/KhachHang.php');

class KhachHangController
{
    // Phương thức hiển thị danh sách khách hàng
    public function hienthi_dskhachhangAction()
    {
        // Lấy danh sách khách hàng từ model
        $customers = KhachHang::allKhachHang();

        // Truyền dữ liệu vào view
        $this->render('hienthi_dskhachhang', ['customers' => $customers]);
    }

    // Hàm render view
    public function render($view, $data)
    {
        // Truyền dữ liệu đến view
        extract($data); // Biến $data sẽ được phân giải thành các biến riêng lẻ
        include('views/khachhang/' . $view . '.php');
    }

    // Phương thức hiển thị form thêm khách hàng
    public function hienthiThemKhachHangAction()
    {
        // Render form thêm khách hàng
        $this->render('themkhachhang', []);
    }
    // Phương thức hiển thị form cập nhật khách hàng
    public function capNhatKhachHangAction()
    {   
        // Render form thêm khách hàng
         $this->render('capnhatkhachhang', []);
    }
    // Phương thức hiển thị form xóa khách hàng
    public function xoaKhachHangAction()
    {
        // Render form thêm khách hàng
         $this->render('xoakhachhang', []);
    }    
    // Phương thức hiển thị form tìm kiếm khách hàng
    public function timKiemKhachHangAction()
    {
        // Render form thêm khách hàng
         $this->render('timkiemkhachhang', []);
    }     
}
?>
