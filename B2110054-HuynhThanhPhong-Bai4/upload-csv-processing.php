<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Lấy phần mở rộng file

// Kiểm tra nếu form đã được submit
if (isset($_POST["submit"])) {
    // Kiểm tra xem file có phải là CSV không
    if ($fileType === 'csv') {
        // Kiểm tra MIME type của file
        $mimeType = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
        if ($mimeType === 'text/csv' || $mimeType === 'application/vnd.ms-excel') {
            echo "File is a valid CSV file.";
            $uploadOk = 1;
        } else {
            echo "File is not a valid CSV file.";
            $uploadOk = 0;
        }
    } else {
        echo "File is not a CSV file.";
        $uploadOk = 0;
    }
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file(
        $_FILES["fileToUpload"]["tmp_name"],
        $target_file
    )) {
        echo "The file " .
            htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . "has been uploaded.";
        echo '<br>';
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "qlbanhang";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        echo '<pre>';
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
                   
        $tmpName =  $target_file;

        $csvLines = file($tmpName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
       
                   // Bỏ qua dòng tiêu đề nếu có
        unset($csvLines[0]);
       
                   // Lặp qua từng dòng của file CSV
        foreach ($csvLines as $line) {
                       // Chuyển dòng CSV thành mảng
            $data = str_getcsv($line);
       
                       // Chuẩn bị câu lệnh SQL để chèn dữ liệu vào bảng
            $sql = "INSERT INTO customers (fullname, email, birthday, reg_date, password, img_profile) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                       
                       // Chuẩn bị câu lệnh với bind params để tránh SQL Injection
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
       
                       // Thực thi câu lệnh
            if ($stmt->execute()) {
                echo "Dòng dữ liệu đã được chèn thành công.<br>";
            } else {
                echo "Lỗi: " . $stmt->error . "<br>";
            }
        }
            
    } else {

        echo "Sorry, there was an error uploading your file.";
    }
}
