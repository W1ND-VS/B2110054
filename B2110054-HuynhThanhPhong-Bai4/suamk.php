<?php
// Bắt đầu session
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlbanhang";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý form khi người dùng gửi yêu cầu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = md5($_POST['old_password']); // Mã hóa mật khẩu cũ
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Lấy mật khẩu từ CSDL
    $stmt = $conn->prepare("SELECT password FROM customers WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Kiểm tra mật khẩu cũ
    if ($old_password !== $stored_password) {
        $error = "Mật khẩu cũ không đúng.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Mật khẩu mới và mật khẩu xác nhận không khớp.";
    } elseif ($old_password === md5($new_password)) {
        $error = "Mật khẩu mới không được giống mật khẩu cũ.";
    } else {
        // Mã hóa mật khẩu mới
        $hashed_new_password = md5($new_password);
        $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_new_password, $_SESSION['id']);
        if ($stmt->execute()) {
            $success = "Mật khẩu đã được cập nhật thành công.";
            echo '<a href="login.php">back to login</a>';
        } else {
            $error = "Có lỗi xảy ra, vui lòng thử lại.";
        }
        $stmt->close();
    }
}

$conn->close();
?>