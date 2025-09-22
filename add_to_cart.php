<?php
session_start();
require_once 'conn.php';

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
         x
        // ตรวจสอบว่ามีตะกร้าใน Session หรือไม่
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // เพิ่มหรืออัปเดตจำนวนสินค้าในตะกร้า
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = array(
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
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