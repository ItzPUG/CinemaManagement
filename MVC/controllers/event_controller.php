<?php
// filepath: /c:/xampp/htdocs/MVC/controllers/event_controller.php
session_start(); // Khởi tạo phiên làm việc

require_once('models/event.php');

class EventController
{
    // Hiển thị chi tiết sự kiện
    public function chiTietEventAction()
    {
        if (!isset($_GET['MaEvent'])) {
            header('Location: index.php');
            exit;
        }

        $MaEvent = $_GET['MaEvent'];

        try {
            $db = DB::getInstance();
            $query = "SELECT * FROM event WHERE MaEvent = :MaEvent";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':MaEvent', $MaEvent);
            $stmt->execute();
            $event = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$event) {
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
        }

        require_once('views/event/chitiet.php');
    }
}
?>