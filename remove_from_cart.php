<?php
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $id) {
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