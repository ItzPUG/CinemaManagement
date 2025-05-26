<?php
class Phim
{
    public $MaPhim;
    public $TenPhim;
    public $TheLoai;
    public $DaoDien;
    public $MoTa;
    public $ThoiLuong;
    public $NamSanXuat;
    public $NgonNgu;
    public $HinhAnh;

    public function __construct($maPhim, $tenPhim, $theLoai, $daoDien, $moTa, $thoiLuong, $namSanXuat, $ngonNgu, $hinhAnh)
    {
        $this->MaPhim = $maPhim;
        $this->TenPhim = $tenPhim;
        $this->TheLoai = $theLoai;
        $this->DaoDien = $daoDien;
        $this->MoTa = $moTa;
        $this->ThoiLuong = $thoiLuong;
        $this->NamSanXuat = $namSanXuat;
        $this->NgonNgu = $ngonNgu;
        $this->HinhAnh = $hinhAnh;
    }

    public static function allPhim()
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM phim');
        $stmt->execute();
        $list = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $list[] = new Phim(
                $item['MaPhim'],
                $item['TenPhim'],
                $item['TheLoai'],
                $item['DaoDien'],
                $item['MoTa'],
                $item['ThoiLuong'],
                $item['NamSanXuat'],
                $item['NgonNgu'],
                $item['HinhAnh']
            );
        }

        return $list;
    }

    public static function findPhim($maPhim)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM phim WHERE MaPhim = :maPhim');
        $stmt->bindParam(':maPhim', $maPhim, PDO::PARAM_STR);
        $stmt->execute();

        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            return new Phim(
                $item['MaPhim'],
                $item['TenPhim'],
                $item['TheLoai'],
                $item['DaoDien'],
                $item['MoTa'],
                $item['ThoiLuong'],
                $item['NamSanXuat'],
                $item['NgonNgu'],
                $item['HinhAnh']
            );
        }

        return null;
    }

    public function updatePhim($data)
    {
        $db = DB::getInstance();
        $query = "UPDATE phim SET 
                    TenPhim = :TenPhim, 
                    TheLoai = :TheLoai, 
                    DaoDien = :DaoDien, 
                    MoTa = :MoTa, 
                    ThoiLuong = :ThoiLuong, 
                    NamSanXuat = :NamSanXuat, 
                    NgonNgu = :NgonNgu,
                    HinhAnh = :HinhAnh
                  WHERE MaPhim = :MaPhim";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':TenPhim', $data['TenPhim']);
        $stmt->bindParam(':TheLoai', $data['TheLoai']);
        $stmt->bindParam(':DaoDien', $data['DaoDien']);
        $stmt->bindParam(':MoTa', $data['MoTa']);
        $stmt->bindParam(':ThoiLuong', $data['ThoiLuong']);
        $stmt->bindParam(':NamSanXuat', $data['NamSanXuat']);
        $stmt->bindParam(':NgonNgu', $data['NgonNgu']);
        $stmt->bindParam(':HinhAnh', $data['HinhAnh']);
        $stmt->bindParam(':MaPhim', $data['MaPhim'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function deletePhim($maPhim)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('DELETE FROM phim WHERE MaPhim = :MaPhim');
        $stmt->bindParam(':MaPhim', $maPhim, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>