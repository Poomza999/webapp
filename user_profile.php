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
                <a href="logout.php">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php">เข้าสู่ระบบ</a>
                <a href="register.php">สมัครสมาชิก</a>
            <?php endif; ?>
        </div>
    </div>
            <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h2>โปรไฟล์ของฉัน</h2>
            </div>
            <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
                $stmt->bind_param("s", $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row) {
            ?>
            <div class="profile-details">
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        
                        <div class="form-group">
                            <label for="username">ชื่อผู้ใช้งาน:</label>
                            <input type="text" id="username" name="userusername" value="<?= $row["username"] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">อีเมล:</label>
                            <input type="email" id="email" name="email" value="<?= $row["email"] ?>">
                        </div>

                        <div class="form-group">
                            <label for="telephone_number">เบอร์โทรศัพท์:</label>
                            <input type="tel" id="telephone_number" name="telephone_number" value="<?= $row["telephone_number"] ?>">
                        </div>
                         
                        <div class="form-group">
                            <label for="address">ที่อยู่:</label>
                            <input type="text" id="address" name="address" value="<?= $row["address"] ?>">
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn">💾 บันทึกการเปลี่ยนแปลง</button>
                            <a href="index.php" class="btn">🔙 กลับหน้าหลัก</a>
                        </div>
                    </form>
                </div>
            <?php } ?>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</body>
