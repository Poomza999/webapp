<?php
session_start();
require_once 'conn.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $telephone_number = $_POST['telephone_number'];
    $user_role = 'user';

    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    $options = ["cost" => 10];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);

    $check_user_sql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($check_user_sql);

    if ($result->num_rows > 0) {
        $error = "ชื่อผู้ใช้นี้มีคนใช้แล้ว กรุณาเลือกชื่ออื่น";
    } else {
        $insert_sql = "INSERT INTO users (username, password, email, user_role, telephone_number) VALUES ('$username', '$hashed_password', '$email', '$user_role', '$telephone_number')";

        if ($conn->query($insert_sql) === TRUE) {
            $success = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
        } else {
            $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .header {
            background-color: #c75454ff;
            padding: 15px 0;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .nav-links {
            margin-top: 10px;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            margin: 50px auto;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #c75454ff;
        }
        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container input[type="email"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #c75454ff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #b71c1c;
        }
        .message {
            margin-bottom: 15px;
        }
        .error-message {
            color: #c75454ff;
        }
        .success-message {
            color: #4CAF50;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>ร้านน้ำจะปั่น</h1>
        <div class="nav-links">
            <a href="index.php">หน้าแรก 🏠</a>
            <a href="login.php">เข้าสู่ระบบ</a>
            <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>สมัครสมาชิก</h2>
            <?php if ($error): ?>
                <div class="message error-message"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message success-message"><?= $success ?></div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <input type="email" name="email" placeholder="อีเมล" required>
                <input type="text" name="telephone_number" placeholder="เบอร์โทรศัพท์" required>
                <input type="submit" value="สมัครสมาชิก">
            </form>
            <a href="index.php" class="back-link">ย้อนกลับ</a>
        </div>
    </div>

</body>
</html>