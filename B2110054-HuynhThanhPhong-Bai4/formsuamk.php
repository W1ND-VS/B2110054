<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi Mật Khẩu</title>
</head>
<body>
    <h2>Đổi Mật Khẩu</h2>
    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color:green;'>$success</p>";
    }
    ?>
    <form method="POST" action="suamk.php">
        <label for="old_password">Mật khẩu cũ:</label>
        <input type="password" name="old_password" required><br><br>
        
        <label for="new_password">Mật khẩu mới:</label>
        <input type="password" name="new_password" required><br><br>
        
        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
        <input type="password" name="confirm_password" required><br><br>
        
        <input type="submit" value="Cập nhật">
    </form>
</body>
</html>