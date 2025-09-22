<?php
session_start();
require_once 'conn.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_role'] = $row['user_role'];

            if ($row['user_role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบชื่อผู้ใช้";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
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
        .form-container input[type="password"] {
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
        .error-message {
            color: #c75454ff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>🍗 ระบบร้านไก่ทอดเกาหลี</h1>
        <div class="nav-links">
            <a href="index.php">หน้าแรก</a>
            <a href="login.php">เข้าสู่ระบบ</a>
            <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>เข้าสู่ระบบ</h2>
            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <input type="submit" value="เข้าสู่ระบบ">
            </form>
             <a href="index.php" class="back-link">ย้อนกลับ</a>
        </div>
    </div>

</body>
</html>