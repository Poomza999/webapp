<?php
session_start();
require_once './conn.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: index_admin.php");
    exit();
}

// ตรวจสอบสิทธิ์การใช้งานว่าเป็น admin หรือไม่
if ($_SESSION['user_role'] == 'admin') {
    header("Location: index_admin.php");
    exit(); // ต้องใส่ exit() ด้วย
} else {
    header("Location: index.php");
    exit();
}

?>

