<?php
session_start();
require_once 'conn.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่ และตะกร้าไม่ว่าง
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$totalPrice = 0;

// คำนวณราคารวมทั้งหมดจากตะกร้าสินค้า
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// เริ่ม Transaction เพื่อให้แน่ใจว่าการบันทึกข้อมูลสำเร็จทั้งหมด
$conn->begin_transaction();

try {
    // บันทึกข้อมูลคำสั่งซื้อหลักลงในตาราง orders
    $sql_order = "INSERT INTO orders (user_id, name, address, phone, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("isssd", $userId, $name, $address, $phone, $totalPrice);
    $stmt_order->execute();

    // ดึง order_id ที่เพิ่งสร้างขึ้นมา
    $orderId = $conn->insert_id;

    // บันทึกรายละเอียดสินค้าแต่ละชิ้นลงในตาราง order_details
    $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);

    foreach ($_SESSION['cart'] as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $stmt_detail->bind_param("iiid", $orderId, $productId, $quantity, $price);
        $stmt_detail->execute();
    }

    // ยืนยัน Transaction
    $conn->commit();

    // ล้างตะกร้าสินค้า
    unset($_SESSION['cart']);

    // ไปยังหน้าแสดงความสำเร็จ
    header('Location: order_success.php');
    exit();

} catch (Exception $e) {
    // ยกเลิก Transaction หากมีข้อผิดพลาด
    $conn->rollback();
    echo "การสั่งซื้อล้มเหลว: " . $e->getMessage();
    exit();
}
?>