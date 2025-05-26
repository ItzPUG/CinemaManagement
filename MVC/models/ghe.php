<?php
class Ghe
{
    public $MaGhe;
    public $SoGhe;
    public $TrangThai;
    public $MaSuatChieu;
    public $SoHang;
    public $SoCot;

    public function __construct($maGhe, $soGhe, $trangThai, $maSuatChieu, $soHang, $soCot)
    {
        $this->MaGhe = $maGhe;
        $this->SoGhe = $soGhe;
        $this->TrangThai = $trangThai;
        $this->MaSuatChieu = $maSuatChieu;
        $this->SoHang = $soHang;
        $this->SoCot = $soCot;
    }

    public static function getGheBySuatChieu($maSuatChieu)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM ghe WHERE MaSuatChieu = :maSuatChieu ORDER BY SoHang, SoCot');
        $stmt->bindParam(':maSuatChieu', $maSuatChieu, PDO::PARAM_INT);
        $stmt->execute();

        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $list[] = new Ghe($item['MaGhe'], $item['SoGhe'], $item['TrangThai'], $item['MaSuatChieu'], $item['SoHang'], $item['SoCot']);
        }
        return $list;
    }

    public static function getGheById($maGhe)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM ghe WHERE MaGhe = :maGhe');
        $stmt->bindParam(':maGhe', $maGhe, PDO::PARAM_INT);
        $stmt->execute();

        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            return new Ghe($item['MaGhe'], $item['SoGhe'], $item['TrangThai'], $item['MaSuatChieu'], $item['SoHang'], $item['SoCot']);
        }
        return null;
    }

    public static function updateTrangThaiGhe($maGhe)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT TrangThai FROM ghe WHERE MaGhe = :maGhe');
        $stmt->bindParam(':maGhe', $maGhe, PDO::PARAM_INT);
        $stmt->execute();
        $seat = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$seat) {
            return false;
        }

        // Cập nhật trạng thái ghế theo quy luật
        $newStatus = '';
        if ($seat->TrangThai == 'Trống') {
            $newStatus = 'Dang Giu';
        } elseif ($seat->TrangThai == 'Dang Giu') {
            $newStatus = 'Da Dat';
        } elseif ($seat->TrangThai == 'Da Dat') {
            $newStatus = 'Trống';
        } else {
            return false;
        }

        $updateStmt = $db->prepare('UPDATE ghe SET TrangThai = :trangThai WHERE MaGhe = :maGhe');
        $updateStmt->bindParam(':trangThai', $newStatus, PDO::PARAM_STR);
        $updateStmt->bindParam(':maGhe', $maGhe, PDO::PARAM_INT);
        return $updateStmt->execute();
    }
}
?>
