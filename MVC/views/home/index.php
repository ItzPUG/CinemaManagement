<?php
// filepath: /c:/xampp/htdocs/MVC/views/home/index.php

// Kết nối cơ sở dữ liệu và lấy danh sách phim và sự kiện
require_once dirname(__DIR__, 2) . '/connection.php';

try {
    $db = DB::getInstance();
    $queryPhim = "SELECT * FROM phim";
    $stmtPhim = $db->query($queryPhim);
    $phims = $stmtPhim->fetchAll(PDO::FETCH_OBJ);

    $queryEvent = "SELECT * FROM event";
    $stmtEvent = $db->query($queryEvent);
    $events = $stmtEvent->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .movie-btn-prev, .movie-btn-next {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            font-size: 20px;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 50%;
        }

        .movie-btn-prev:hover, .movie-btn-next:hover {
            background-color: rgba(0, 0, 0, 0.1);
            transform: scale(1.1);
        }

        .movie-selection {
            overflow: hidden;
            white-space: nowrap;
            padding: 10px;
            scroll-behavior: smooth; /* Đảm bảo cuộn mượt */
        }

        .movie-item {
            display: inline-block;
            margin-right: 15px;
            text-align: center;
        }

        .movie-item img {
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .movie-item img:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .event-item {
            text-align: center;
            margin-bottom: 20px;
        }

        .event-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-item img:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .event-item p {
            margin-top: 10px;
            font-weight: bold;
        }

        /* Thêm CSS cho tiêu đề "MOVIE SELECTION" */
        .movie-selection-title {
            text-align: center;
            font-family: serif; /* Thay đổi font chữ */
            color: #ff5733; /* Thay đổi màu chữ */
            font-weight: bolder;
            font-size: 24px; /* Thay đổi kích thước chữ */
            margin-bottom: 20px;
        }
    </style>
    <script>
        function scrollMovies(direction) {
            const movieList = document.getElementById('movieList');
            const scrollAmount = 300; // Khoảng cách cuộn mỗi lần
            if (direction === 'prev') {
                movieList.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else if (direction === 'next') {
                movieList.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3  class="movie-selection-title" style="text-align: center;">GALAXY CGV</h3>
                
                <!-- Carousel -->
                <div id="cuon" class="carousel slide" data-ride="carousel" data-interval="2000"> <!-- Chỉnh interval ở đây -->
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#cuon" data-slide-to="0" class="active"></li>
                        <li data-target="#cuon" data-slide-to="1"></li>
                        <li data-target="#cuon" data-slide-to="2"></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner" style="text-align: center;">
                        <div class="carousel-item active">
                            <img src="resources/images/natra.jpg" alt="anh1" width="900" height="400">
                            <div class="carousel-caption">
                                <p><i>Na Tra: Ma Đồng Náo Hải</i></p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="resources/images/flow.jpg" alt="anh2" width="900" height="400">
                            <div class="carousel-caption">
                                <p><i>Lạc Trôi</i></p>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="resources/images/W2.jpg" alt="anh3" width="900" height="400">
                            <div class="carousel-caption">
                                <p>Wuthering Waves</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <!-- Movie Section -->
        <div class="row">
            <div class="col-12">
                <h3 class="movie-selection-title">==========MOVIE SELECTION==========</h3>
                <div class="position-relative">
                    <!-- Button Previous -->
                    <button class="btn btn-light movie-btn-prev" onclick="scrollMovies('prev')" style="position: absolute; top: 50%; left: 0; transform: translateY(-50%); z-index: 100;">&#8249;</button>
                    
                    <div class="movie-selection" id="movieList" style="overflow: hidden; white-space: nowrap; padding: 10px;">
                        <?php foreach ($phims as $film): ?>
                            <div class="movie-item" style="display: inline-block; margin-right: 15px; text-align: center;">
                                <a href="index.php?controllers=phim&action=chiTietPhimAction&MaPhim=<?php echo $film->MaPhim; ?>">
                                    <img src="resources/images/<?php echo $film->HinhAnh; ?>" alt="<?php echo $film->TenPhim; ?>" width="200" height="300">
                                    <p><?php echo $film->TenPhim; ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Button Next -->
                    <button class="btn btn-light movie-btn-next" onclick="scrollMovies('next')" style="position: absolute; top: 50%; right: 0; transform: translateY(-50%); z-index: 100;">&#8250;</button>
                </div>
            </div>
        </div>

        <!-- Event Section -->
        <div class="row">
            <div class="col-12">
                <h3 class="movie-selection-title" style="text-align: center;">EVENT</h3>
                <div class="row">
                    <?php foreach ($events as $event): ?>
                        <div class="col-md-4 col-sm-6 event-item">
                            <a href="index.php?controllers=event&action=chiTietEventAction&MaEvent=<?php echo $event->MaEvent; ?>">
                                <img src="resources/images/<?php echo $event->HinhAnh; ?>" alt="<?php echo $event->TenEvent; ?>">
                                <p><?php echo $event->TenEvent; ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>