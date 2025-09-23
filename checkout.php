<?php
session_start();
require_once 'conn.php';

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; color: #333; }
        .header { background-color: #c75454ff; padding: 15px 0; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; }
        .header .nav-links { margin-top: 10px; }
        .header a { color: white; text-decoration: none; margin: 0 15px; font-size: 16px; }
        .header a:hover { text-decoration: underline; }
        .container { padding: 20px; text-align: center; }
        .checkout-container { width: 80%; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: left; }
        .checkout-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .checkout-table th, .checkout-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .checkout-table th { background-color: #f2f2f2; }
        .checkout-table .total-row { font-weight: bold; background-color: #e0e0e0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .submit-btn { background-color: #c75454ff; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin-top: 10px; cursor: pointer; border-radius: 5px; width: 100%; }
    </style>
</head>
<body>

    <div class="header">
        <h1>🧋ร้านน้ำจะปั่น🧋</h1>
        <div class="nav-links">
            <a href="index.php">🏠หน้าแรก🏠</a>
            <a href="cart.php">ตะกร้าสินค้า 🛒</a>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </div>

    <div class="container">
        <h2>ยืนยันการสั่งซื้อ</h2>
        <div class="checkout-container">
            <h3>รายการสินค้า</h3>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>สินค้า</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                        <th>รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($_SESSION['cart'] as $item) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $totalPrice += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= number_format($item['price'], 2); ?> บาท</td>
                            <td><?= $item['quantity']; ?></td>
                            <td><?= number_format($subtotal, 2); ?> บาท</td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">รวมทั้งหมด:</td>
                        <td><?= number_format($totalPrice, 2); ?> บาท</td>
                    </tr>
                </tbody>
            </table>

                    <?php
            $users_id = $_SESSION['id'];
        ?>
        <?php
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->bind_param("s", $users_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
        ?>  
            <h3>ข้อมูลสำหรับจัดส่ง</h3>
            <form action="process_order.php" method="POST">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                
                <div class="form-group">
                    <label for="username">ชื่อผู้ใช้งาน:</label>
                    <input type="text" disabled id="username" name="userusername" value="<?= $row["username"] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" disabled id="email" name="email" value="<?= $row["email"] ?>">
                </div>

                <div class="form-group">
                    <label for="telephone_number">เบอร์โทรศัพท์:</label>
                    <input type="tel" disabled id="telephone_number" name="telephone_number" value="<?= $row["telephone_number"] ?>">
                </div>

                <div class="form-group">
                    <label for="address">ที่อยู่:</label>
                    <input type="text" disabled id="address" name="address" value="<?= $row["address"] ?>">
                </div>
                <button type="submit" class="submit-btn">ยืนยันการสั่งซื้อ</button>
            </form>
        </div>
    <?php } ?>
</div>

</body>
</html>