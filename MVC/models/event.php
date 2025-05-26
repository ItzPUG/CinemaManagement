<?php
// filepath: /c:/xampp/htdocs/MVC/models/event.php

class Event
{
    public $MaEvent;
    public $TenEvent;
    public $MoTa;
    public $HinhAnh;
    public $NgayBatDau;
    public $NgayKetThuc;

    public function __construct($MaEvent, $TenEvent, $MoTa, $HinhAnh, $NgayBatDau, $NgayKetThuc)
    {
        $this->MaEvent = $MaEvent;
        $this->TenEvent = $TenEvent;
        $this->MoTa = $MoTa;
        $this->HinhAnh = $HinhAnh;
        $this->NgayBatDau = $NgayBatDau;
        $this->NgayKetThuc = $NgayKetThuc;
    }

    public static function all()
    {
        $list = [];
        $db = DB::getInstance();
        $req = $db->query('SELECT * FROM event');

        foreach ($req->fetchAll() as $item) {
            $list[] = new Event($item['MaEvent'], $item['TenEvent'], $item['MoTa'], $item['HinhAnh'], $item['NgayBatDau'], $item['NgayKetThuc']);
        }

        return $list;
    }

    public static function find($MaEvent)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM event WHERE MaEvent = :MaEvent');
        $req->execute(array('MaEvent' => $MaEvent));
        $item = $req->fetch();

        if ($item) {
            return new Event($item['MaEvent'], $item['TenEvent'], $item['MoTa'], $item['HinhAnh'], $item['NgayBatDau'], $item['NgayKetThuc']);
        }

        return null;
    }
}
?>