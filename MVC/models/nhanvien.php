<?php
class NhanVien
{
    public $MaNhanVien;
    public $TenNhanVien;
    public $CaLamViec;
    public $SoDienThoai;
    public $LuongCoBan;
    public $ChucVu;

    // Hàm khởi tạo cho lớp NhanVien
    function __construct($ma, $ten, $ca, $sdt, $luong, $chucvu)
    {
        $this->MaNhanVien = $ma;
        $this->TenNhanVien = $ten;
        $this->CaLamViec = $ca;
        $this->SoDienThoai = $sdt;
        $this->LuongCoBan = $luong;
        $this->ChucVu = $chucvu;
    }

    // Phương thức tĩnh để lấy tất cả nhân viên
    public static function allNhanVien()
    {
        $list = [];
        $db = DB::getInstance(); // Sử dụng kết nối cơ sở dữ liệu thông qua lớp DB
        $result = $db->prepare('SELECT * FROM bophanbanve');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        // Duyệt qua từng dòng kết quả và tạo đối tượng NhanVien
        foreach ($result->fetchAll() as $item) {
            $list[] = new NhanVien(
                $item['MaNhanVien'],
                $item['TenNhanVien'],
                $item['CaLamViec'],
                $item['SoDienThoai'],
                $item['LuongCoBan'],
                $item['ChucVu']
            );
        }

        return $list;
    }
}
?>
