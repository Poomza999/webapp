<?php
session_start();

require("./conn.php");

$isLoggedIn = isset($_SESSION['id']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; color: #333; }
        .header { background-color: #c75454ff; padding: 15px 0; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; }
        .header .nav-links { margin-top: 10px; }
        .header a { color: white; text-decoration: none; margin: 0 15px; font-size: 16px; }
        .header a:hover { text-decoration: underline; }
        .container { padding: 20px; text-align: center; }
        .cart-container { width: 80%; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .cart-table th, .cart-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .cart-table th { background-color: #f2f2f2; }
        .cart-table .total-row { font-weight: bold; }
        .total-price-text { text-align: right; padding-right: 20px; }
        .action-btn { background-color: #f44336; color: white; border: none; padding: 8px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin: 4px 2px; cursor: pointer; border-radius: 5px; }
        .update-btn { background-color: #4CAF50; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .quantity-input { width: 50px; text-align: center; }
        .checkout-btn { background-color: #c75454ff; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin-top: 20px; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>🧋ร้านน้ำจะปั่น🧋</h1>
        <div class="nav-links">
            <a href="index.php">🏠หน้าแรก🏠</a>
            <a href="cart.php">ตะกร้าสินค้า 🛒</a>
            <?php if ($isLoggedIn): ?>
                <a href="logout.php">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php">เข้าสู่ระบบ</a>
                <a href="register.php">สมัครสมาชิก</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h2>ตะกร้าสินค้า</h2>
        <div class="cart-container">
            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>สินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $totalPrice += $subtotal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']); ?></td>
                                <td><?= number_format($item['price'], 2); ?> บาท</td>
                                <td>
                                    <a href="update_cart.php?action=decrease&id=<?= $id ?>" class="update-btn">-</a>
                                    <?= $item['quantity']; ?>
                                    <a href="update_cart.php?action=increase&id=<?= $id ?>" class="update-btn">+</a>
                                </td>
                                <td><?= number_format($subtotal, 2); ?> บาท</td>
                                <td>
                                    <a href="remove_from_cart.php?id=<?= $id ?>" class="action-btn">ลบ</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">รวมทั้งหมด:</td>
                            <td colspan="2"><?= number_format($totalPrice, 2); ?> บาท</td>
                        </tr>
                    </tfoot>
                </table>
                <a href="checkout.php" class="checkout-btn">ดำเนินการชำระเงิน</a>
            <?php else: ?>
                <p>ไม่มีสินค้าในตะกร้า</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>