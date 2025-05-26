// taikhoan/dangxuat.php
<?php
session_start();
session_unset(); // Xóa tất cả session
session_destroy(); // Hủy phiên làm việc
header('Location: /MVC/index.php'); // Điều hướng về trang đăng nhập
exit;
?>
