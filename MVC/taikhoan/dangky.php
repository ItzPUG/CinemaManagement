<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_BCRYPT);
    $vai_tro = 'user';

    $conn = new mysqli('localhost', 'root', '', 'qlyrap');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $check_sql = "SELECT * FROM tai_khoan WHERE ten_dang_nhap = '$ten_dang_nhap'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $message = "Tên đăng nhập đã tồn tại! Vui lòng chọn tên đăng nhập khác.";
    } else {
        $sql = "INSERT INTO tai_khoan (ten_dang_nhap, mat_khau, vai_tro) VALUES ('$ten_dang_nhap', '$mat_khau', '$vai_tro')";
        if ($conn->query($sql) === TRUE) {
            $message = "Đăng ký thành công!";
        } else {
            $message = "Lỗi: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../resources/images/login.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-end; /* Di chuyển loader và form sang bên phải */
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: rgba(237, 238, 238, 0.9);
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            opacity: 0;
            transform: translateY(50px);
            margin-right: 150px; /* Điều chỉnh khoảng cách từ bên phải */
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Roboto'; /* Thay đổi font chữ */
            color: #ff5733; /* Thay đổi màu chữ */
            font-weight: bolder;
            font-size: 24px; /* Thay đổi kích thước chữ */
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .success-message, .error-message {
            margin-top: 10px;
            text-align: center;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 80px;
            height: 80px;
            animation: spin 2s linear infinite;
            position: absolute;
            top: 40%;
            right: 400px; /* Điều chỉnh khoảng cách từ bên phải */
            transform: translateY(-50%);
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <div class="register-container">
        <h2>Đăng Ký Tài Khoản</h2>
        <form method="POST">
            <div class="form-group">
                <label for="ten_dang_nhap">Tên đăng nhập:</label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required>
            </div>
            <div class="form-group">
                <label for="mat_khau">Mật khẩu:</label>
                <input type="password" id="mat_khau" name="mat_khau" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <?php if (!empty($message)) : ?>
                <div class="<?php echo strpos($message, 'thành công') !== false ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.loader').fadeOut();
                gsap.to('.register-container', {opacity: 1, y: 0, duration: 1});
            }, 2000); // Thời gian chờ 2 giây trước khi hiển thị form đăng ký
        });
    </script>
</body>
</html>