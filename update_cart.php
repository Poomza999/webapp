<?php
session_start();

// ตรวจสอบว่ามี action และ id หรือไม่
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    // ตรวจสอบว่ามีตะกร้าสินค้าและสินค้านั้นอยู่ในตะกร้าหรือไม่
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$id])) {
        
        switch($action) {
            case 'increase':
                // เพิ่มจำนวนสินค้า
                $_SESSION['cart'][$id]['quantity']++;
                break;
                
            case 'decrease':
                // ลดจำนวนสินค้า
                if ($_SESSION['cart'][$id]['quantity'] > 1) {
                    $_SESSION['cart'][$id]['quantity']--;
                } else {
                    // ถ้าจำนวนเป็น 1 แล้วลดอีก ให้ลบสินค้าออกจากตะกร้า
                    unset($_SESSION['cart'][$id]);
                }
                break;
                
            case 'remove':
                // ลบสินค้าออกจากตะกร้าทั้งหมด
                unset($_SESSION['cart'][$id]);
                break;
                
            default:
                // action ไม่ถูกต้อง
                break;
        }
    }
}

// เปลี่ยนเส้นทางกลับไปยังหน้าตะกร้า
header('Location: cart.php');
exit();
?>