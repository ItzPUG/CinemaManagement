<?php
// filepath: /MVC/views/event/chitiet.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sự Kiện</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-primary"><?php echo $event->TenEvent; ?></h1>
        <img src="resources/images/<?php echo $event->HinhAnh; ?>" alt="<?php echo $event->TenEvent; ?>" width="400" height="300">
        <p><?php echo $event->MoTa; ?></p>
        <p><strong>Ngày Bắt Đầu:</strong> <?php echo $event->NgayBatDau; ?></p>
        <p><strong>Ngày Kết Thúc:</strong> <?php echo $event->NgayKetThuc; ?></p>
        <a href="index.php" class="btn btn-primary">Quay lại</a>
    </div>
</body>
</html>