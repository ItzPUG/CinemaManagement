<?php
class Ve
{
    public $MaVe;
    public $SoGhe;
    public $TrangThai;
    public $MaSuatChieu;
    public $MaKhachHang;
    public $MaPhim;

    // Hàm khởi tạo cho lớp Ve
    function __construct($maVe, $soGhe, $trangThai, $maSuatChieu, $maKhachHang, $maPhim)
    {
        $this->MaVe = $maVe;
        $this->SoGhe = $soGhe;
        $this->TrangThai = $trangThai;
        $this->MaSuatChieu = $maSuatChieu;
        $this->MaKhachHang = $maKhachHang;
        $this->MaPhim = $maPhim;
    }

    // Phương thức lấy tất cả vé
    public static function allVe()
    {
        $list = [];
        $db = DB::getInstance(); // Lớp DB kết nối cơ sở dữ liệu
        $result = $db->prepare('SELECT * FROM ve');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        // Duyệt qua các kết quả và tạo đối tượng Ve
        foreach ($result->fetchAll() as $item) {
            $list[] = new Ve(
                $item['MaVe'],
                $item['SoGhe'],
                $item['TrangThai'],
                $item['MaSuatChieu'],
                $item['MaKhachHang'],
                $item['MaPhim']
            );
        }

        return $list;
    }

    // Phương thức tìm vé theo MaVe
    public static function findVe($maVe)
    {
        $db = DB::getInstance();
        $result = $db->prepare('SELECT * FROM ve WHERE MaVe = :maVe');
        $result->bindParam(':maVe', $maVe);
        $result->execute();

        $item = $result->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            return new Ve(
                $item['MaVe'],
                $item['SoGhe'],
                $item['TrangThai'],
                $item['MaSuatChieu'],
                $item['MaKhachHang'],
                $item['MaPhim']
            );
        }
        return null;
    }

    // Phương thức cập nhật vé
    public function updateVe($data)
    {
        $db = DB::getInstance();
        $query = "UPDATE ve SET SoGhe = :SoGhe, TrangThai = :TrangThai, MaSuatChieu = :MaSuatChieu, MaKhachHang = :MaKhachHang, MaPhim = :MaPhim WHERE MaVe = :MaVe";
        $stmt = $db->prepare($query);

        // Ràng buộc tham số với dữ liệu từ $data
        $stmt->bindParam(':SoGhe', $data['SoGhe']);
        $stmt->bindParam(':TrangThai', $data['TrangThai']);
        $stmt->bindParam(':MaSuatChieu', $data['MaSuatChieu']);
        $stmt->bindParam(':MaKhachHang', $data['MaKhachHang']);
        $stmt->bindParam(':MaPhim', $data['MaPhim']);
        $stmt->bindParam(':MaVe', $data['MaVe']);

        return $stmt->execute();
    }

    // Phương thức xóa vé
    public static function deleteVe($maVe)
    {
        $db = DB::getInstance();
        $query = "DELETE FROM ve WHERE MaVe = :MaVe";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':MaVe', $maVe);
        return $stmt->execute();
    }
}
?>
