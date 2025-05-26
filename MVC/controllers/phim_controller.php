<?php
require_once('models/phim.php');

class PhimController
{
    public function hienthi_dsphimAction()
    {
        $phims = Phim::allPhim(); // Lấy danh sách phim từ model
        $this->render('dsphim', ['phims' => $phims]); // Truyền vào view
    }

    public function hienthiThemPhimAction()
    {
        $this->render('themphim', []); // Render form thêm phim
    }

    public function capNhatPhimActionForm()
    {
        if (isset($_GET['MaPhim']) && !empty($_GET['MaPhim'])) {
            $movie = Phim::findPhim($_GET['MaPhim']); // Lấy phim cần sửa
            if ($movie) {
                $this->render('capnhatphim', ['movie' => $movie]);
            } else {
                echo "Không tìm thấy phim với mã phim: " . htmlspecialchars($_GET['MaPhim']);
            }
        } else {
            echo "Không có mã phim hợp lệ.";
        }
    }

    // Hiển thị chi tiết phim
    public function chiTietPhimAction()
    {
        if (isset($_GET['MaPhim'])) {
            $maPhim = $_GET['MaPhim'];
            $phim = Phim::findPhim($maPhim);

            if ($phim) {
                require_once('views/phim/chitietphim.php');
            } else {
                echo "Không tìm thấy phim.";
            }
        } else {
            echo "Không có mã phim được cung cấp.";
        }
    }

    public function capNhatPhimActionHandler()
    {
        if (isset($_POST['MaPhim']) && !empty($_POST['MaPhim'])) {
            $movie = new Phim(
                $_POST['MaPhim'],
                $_POST['TenPhim'],
                $_POST['TheLoai'],
                $_POST['DaoDien'],
                $_POST['MoTa'],
                $_POST['ThoiLuong'],
                $_POST['NamSanXuat'],
                $_POST['NgonNgu'],
                $_POST['HinhAnh']
            );

            if ($movie->updatePhim($_POST)) { // Cập nhật phim thành công
                header('Location: index.php?controllers=phim&action=hienthi_dsphimAction');
                exit();
            } else {
                echo "Có lỗi xảy ra khi cập nhật phim.";
            }
        } else {
            echo "Dữ liệu không hợp lệ.";
        }
    }

    public function xoaPhimActionHandler()
    {
        if (isset($_GET['MaPhim']) && !empty($_GET['MaPhim'])) {
            if (Phim::deletePhim($_GET['MaPhim'])) {
                header('Location: index.php?controllers=phim&action=hienthi_dsphimAction');
                exit();
            } else {
                echo "Không thể xóa phim.";
            }
        } else {
            echo "Mã phim không hợp lệ.";
        }
    }

    public function timKiemPhimAction()
    {
        // Render form tìm kiếm vé
        $this->render('timkiemphim', []);
    }

    public function render($view, $data)
    {
        extract($data); // Biến $data sẽ phân giải thành các biến
        include('views/phim/' . $view . '.php'); // Gọi file view tương ứng
    }
}
?>