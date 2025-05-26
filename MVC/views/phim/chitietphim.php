<?php
// views/phim/chitietphim.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4"><?php echo $phim->TenPhim; ?></h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <img src="resources/images/<?php echo $phim->HinhAnh; ?>" alt="<?php echo $phim->TenPhim; ?>" class="img-fluid">
            </div>
            <div class="col-md-8">
                <p><strong>Thể Loại:</strong> <?php echo $phim->TheLoai; ?></p>
                <p><strong>Đạo Diễn:</strong> <?php echo $phim->DaoDien; ?></p>
                <p><strong>Mô Tả:</strong> <?php echo $phim->MoTa; ?></p>
                <p><strong>Thời Lượng:</strong> <?php echo $phim->ThoiLuong; ?> phút</p>
                <p><strong>Năm Sản Xuất:</strong> <?php echo $phim->NamSanXuat; ?></p>
                <p><strong>Ngôn Ngữ:</strong> <?php echo $phim->NgonNgu; ?></p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Quay lại</a>
        </div>
    </div>
</body>
</html>