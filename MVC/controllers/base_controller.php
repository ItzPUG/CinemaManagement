<?php
class BaseController
{
    protected $folder;
    protected $pageURL;

    // Hàm hiển thị kết quả ra cho người dùng.
    function render($file, $data = array())
    {
        // Kiểm tra file gọi đến có tồn tại hay không
        $view_file = 'views/' . $this->folder . '/' . $file . '.php';
        
        if (is_file($view_file)) {
            extract($data); // Trích xuất dữ liệu từ mảng $data thành các biến
            ob_start(); // Bắt đầu buffer để lưu output
            require_once($view_file); // Gọi file view
            $content = ob_get_clean(); // Lấy nội dung của view và lưu vào $content
            
            // Gọi file layout
            require_once('views/layouts/application.php');
        } else {
            // Nếu file view không tồn tại, chuyển hướng tới trang lỗi
            header('Location: index.php?controller=home&action=error');
        }
    }
}