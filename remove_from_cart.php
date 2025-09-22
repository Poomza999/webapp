<?php
session_start();

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
}

// Redirect กลับไปหน้าตะกร้าสินค้า
header("Location: cart.php");
exit();
?>