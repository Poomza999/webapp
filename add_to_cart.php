<?php
session_start();
require_once 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // ตรวจสอบว่ามีตะกร้าใน Session หรือไม่
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // เพิ่มหรืออัปเดตจำนวนสินค้าในตะกร้า 
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = array(
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => 1
            );
        }
    }
}

// เปลี่ยนเส้นทางกลับไปที่หน้าตะกร้า
header('Location: cart.php');
exit();
?>