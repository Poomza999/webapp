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
    <title>ร้านน้ำจะปั่น</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>🧋ร้านน้ำจะปั่น🧋</h1>
        <h3><a href="user_profile.php">👤โปรไฟล์👤</a></h3>
        <div class="nav-links">
            <a href="index.php">🏠หน้าแรก🏠</a>
            <?php if ($isLoggedIn): ?>
                <a href="cart.php">ตะกร้าสินค้า 🛒</a>
                <a href="logout.php">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php">เข้าสู่ระบบ</a>
                <a href="register.php">สมัครสมาชิก</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="product-grid">
            <?php
            // $rnd = rand(1, 12);
            // $rnd2 = rand(0, 12);
            // $sql = "SELECT * FROM products LIMIT $rnd, $rnd2";
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
            <div class="product-card">
                <img src="<?= $row["picture_url"] ?>">
                <h3><?= $row["name"] ?></h3>
                <p><?= $row["description"] ?></p>
                <div class="price">ราคา: <?= $row["price"] ?> บาท</div>
                <?php
                if ($isLoggedIn) {
                ?>
                <a href="add_to_cart.php?id=<?= $row["id"] ?>" class="btn">เพิ่มลงในตะกร้า</a>
                <?php
                } else {
                ?>
                <a href="login.php" class="btn">เข้าสู่ระบบ</a>
                <?php
                }
                ?>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</body>
</html>