<?php
$controllers = array(
    'home' => ['index', 'error'],
    'khachhang' => ['timKiemKhachHangAction', 'xoaKhachHangAction', 'capNhatKhachHangAction', 'hienthiThemKhachHangAction', 'hienthi_dskhachhangAction'],
    've' => ['hienthi_dsveAction', 'hienthiThemVeAction', 'capNhatVeActionForm', 'capNhatVeActionHandler', 'xoaVeAction', 'xoaVeActionHandler', 'timKiemVeAction'],
    'phim' => ['hienthi_dsphimAction', 'hienthiThemPhimAction', 'xoaPhimAction', 'capNhatPhimActionForm', 'capNhatPhimActionHandler', 'xoaPhimActionHandler', 'timKiemPhimAction', 'chiTietPhimAction'],
    'nhanvien' => ['hienthi_dsNhanVienAction', 'hienthiThemNhanVienAction', 'capNhatNhanVienAction', 'xoaNhanVienAction', 'timKiemNhanVienAction'],
    'event' => ['chiTietEventAction'],
    'ghe' => ['datGheAction', 'datGhe', 'xuLyDatGheAction']
);

// Kiểm tra controller và action
if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    $controller = 'home';
    $action = 'error';
}

// Gọi file controller
include_once('controllers/' . $controller . '_controller.php');

// Tạo tên class từ tên controller
if (is_string($controller)) { // Kiểm tra xem $controller có phải là chuỗi không
    $tenClass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';

    // Khởi tạo controller và gọi action
    if (class_exists($tenClass)) {
        $controller = new $tenClass;
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            die("Action $action không tồn tại trong controller $tenClass.");
        }
    } else {
        die("Class $tenClass không tồn tại.");
    }
} else {
    die("Tên controller không hợp lệ.");
}
?>
