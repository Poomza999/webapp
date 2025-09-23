<?php
session_start();
require_once './conn.php';

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard ผู้ดูแลระบบ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .header {
            background-color: #d32f2f;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
        }
        .dashboard-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        .menu-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
            text-decoration: none;
            color: #d32f2f;
            font-weight: bold;
            font-size: 18px;
            transition: transform 0.3s;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .welcome-message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Dashboard ผู้ดูแลระบบ</h1>
        <div>
            <span> สวัสดี, <?= $_SESSION['username'] ?></span>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </div>

    <div class="container">
        <h2 class="welcome-message">ยินดีต้อนรับเข้าสู่ระบบจัดการร้าน</h2>
        
        <div class="dashboard-menu">
            <a href="product_edit.php" class="menu-card">
                <h3>🛍️ จัดการสินค้า</h3>
                <p>เพิ่ม แก้ไข หรือลบสินค้า</p>
            </a>
            <a href="user_edit.php" class="menu-card">
                <h3>👤 จัดการผู้ใช้งาน</h3>
                <p>จัดการข้อมูลและสิทธิ์ผู้ใช้</p>
            </a>
        </div>
    </div>

</body>
</html>
