<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Chỉ khởi động phiên nếu chưa có phiên nào
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Quản lý</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thêm Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        /* Tùy chỉnh thêm màu nền và khoảng cách cho Navbar */
        .navbar {
            background: linear-gradient(45deg, rgb(114, 180, 211), #f1f1f1);
            color: white;
        }

        .navbar-nav .nav-item .nav-link {
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
        }

        .navbar-nav .nav-item .nav-link:hover {
            background-color: rgb(71, 38, 219);
            color: #fff;
        }

        .navbar-brand {
            font-family: 'Press Start 2P', cursive;
            font-size: 20px;
            color: #fff;
        }

        .navbar-brand:hover {
            color: #f57c00;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #f57c00;
            font-weight: bold;
            font-family: cursive;
        }


        .dropdown-menu {
            min-width: 200px;
            font-family: cursive;
            font-weight: bold;
        }
        .nav-item {
            font-family: cursive;
            font-weight: bolder;
        }
    </style>
    <script>
        function confirmLogout(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của nút
            if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
                window.location.href = event.target.href; // Điều hướng đến trang đăng xuất
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="index.php">HOME</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <!-- Trái -->
                        <ul class="navbar-nav ml-2 mr-auto">
                            <!-- Các mục sẵn có của bạn -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarkhachhang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Khách hàng</a>
                                <div class="dropdown-menu" aria-labelledby="navbarkhachhang">
                                    <a class="dropdown-item" href="index.php?controllers=khachhang&action=hienthi_dskhachhangAction">Danh mục khách hàng</a>
                                    <a class="dropdown-item" href="index.php?controllers=khachhang&action=hienthiThemKhachHangAction">Thêm khách hàng</a>
                                    <a class="dropdown-item" href="index.php?controllers=khachhang&action=timKiemKhachHangAction">Tìm kiếm khách hàng</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarsanpham" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Vé</a>
                                <div class="dropdown-menu" aria-labelledby="navbarsanpham">
                                    <a class="dropdown-item" href="index.php?controllers=ve&action=hienthi_dsveAction">Danh mục vé</a>
                                    <a class="dropdown-item" href="index.php?controllers=ve&action=hienthiThemVeAction">Thêm vé</a>
                                    <a class="dropdown-item" href="index.php?controllers=ve&action=timKiemVeAction">Tìm kiếm vé</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarkhachhang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Nhân viên</a>
                                <div class="dropdown-menu" aria-labelledby="navbarkhachhang">
                                    <a class="dropdown-item" href="index.php?controllers=nhanvien&action=hienthi_dsNhanVienAction">Danh mục nhân viên</a>
                                    <a class="dropdown-item" href="index.php?controllers=nhanvien&action=hienthiThemNhanVienAction">Thêm nhân viên</a>
                                    <a class="dropdown-item" href="index.php?controllers=nhanvien&action=timKiemNhanVienAction">Tìm kiếm nhân viên</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarsanpham" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Phim</a>
                                <div class="dropdown-menu" aria-labelledby="navbarsanpham">
                                    <a class="dropdown-item" href="index.php?controllers=phim&action=hienthi_dsphimAction">Danh mục phim</a>
                                    <a class="dropdown-item" href="index.php?controllers=phim&action=hienthiThemPhimAction">Thêm phim</a>
                                    <a class="dropdown-item" href="index.php?controllers=phim&action=timKiemPhimAction">Tìm kiếm phim</a>
                                    <a class="dropdown-item" href="index.php?controllers=ghe&action=datGheAction">Đặt ghế</a>
                                </div>
                            </li>
                        </ul>
                        <!-- Phải -->
                        <nav class="navbar">
                            <ul class="navbar-nav ml-auto">
                                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Hi: <?php echo htmlspecialchars($_SESSION['ten_dang_nhap']); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="taikhoan/dangxuat.php" onclick="confirmLogout(event)">Đăng xuất</a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="taikhoan/dangnhap.php">Đăng nhập</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="taikhoan/dangky.php">Đăng ký</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JS và jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>