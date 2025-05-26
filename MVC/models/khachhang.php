
<?php
class KhachHang
{
    public $MaKhachHang;
    public $TenKhachHang;
    public $Email;
    public $SoDienThoai;
    public $DiaChi;
    public $NgayDangKy;

    // Hàm khởi tạo cho lớp KhachHang
    function __construct($ma, $ten, $email, $sdt, $dc, $ngaydk)
    {
        $this->MaKhachHang = $ma;
        $this->TenKhachHang = $ten;
        $this->Email = $email;
        $this->SoDienThoai = $sdt;
        $this->DiaChi = $dc;
        $this->NgayDangKy = $ngaydk;
    }

    // Phương thức tĩnh để lấy tất cả khách hàng
    public static function allKhachHang()
    {
        $list = [];
        $db = DB::getInstance(); // Sử dụng kết nối cơ sở dữ liệu thông qua lớp DB
        $result = $db->prepare('SELECT * FROM khach_hang');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        // Duyệt qua từng dòng kết quả và tạo đối tượng KhachHang
        foreach ($result->fetchAll() as $item) {
            // In dữ liệu ra màn hình để kiểm tra
            echo '<pre>';
            echo '</pre>';

            $list[] = new KhachHang(
                $item['MaKhachHang'], 
                $item['TenKhachHang'], 
                $item['Email'], 
                $item['SoDienThoai'], 
                // Xử lý giá trị NULL nếu có
                isset($item['DiaChi']) && $item['DiaChi'] != NULL ? $item['DiaChi'] : 'Địa chỉ chưa có',
                isset($item['NgayDangKy']) && $item['NgayDangKy'] != NULL ? $item['NgayDangKy'] : '0000-00-00'
            );
        }

        return $list; // Đảm bảo return nằm bên trong hàm
    }
}
?>

