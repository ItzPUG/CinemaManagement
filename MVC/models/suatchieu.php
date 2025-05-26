<?php

class SuatChieu
{
    public $MaSuatChieu;
    public $MaPhim;
    public $NgayChieu;
    public $GioChieu;
    public $PhongChieu;

    public function __construct($maSuatChieu, $maPhim, $ngayChieu, $gioChieu, $phongChieu)
    {
        $this->MaSuatChieu = $maSuatChieu;
        $this->MaPhim = $maPhim;
        $this->NgayChieu = $ngayChieu;
        $this->GioChieu = $gioChieu;
        $this->PhongChieu = $phongChieu;
    }

    public static function getAllSuatChieu()
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM suatchieu ORDER BY NgayChieu, GioChieu');
        $stmt->execute();

        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $list[] = new SuatChieu($item['MaSuatChieu'], $item['MaPhim'], $item['NgayChieu'], $item['GioChieu'], $item['PhongChieu']);
        }
        return $list;
    }

    public static function getSuatChieuById($maSuatChieu)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM suatchieu WHERE MaSuatChieu = :maSuatChieu');
        $stmt->bindParam(':maSuatChieu', $maSuatChieu, PDO::PARAM_INT);
        $stmt->execute();

        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            return new SuatChieu($item['MaSuatChieu'], $item['MaPhim'], $item['NgayChieu'], $item['GioChieu'], $item['PhongChieu']);
        }
        return null;
    }
}
?>
