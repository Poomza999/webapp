<?php
session_start();
require("./conn.php");

$isLoggedIn = isset($_SESSION['id']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_users"])) {
    $id = $_POST['id'];
    $username = $_POST['userusername'];
    $email = $_POST['email'];
    $telephone_number = $_POST["telephone_number"];
    $address = $_POST["address"];

    // i int
    // s string, text
    // d double, float
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, telephone_number = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $telephone_number, $address, $id);
    if ($stmt->execute()) {
        $success_message = "แก้ไขข้อมูลสำเร็จ!";
        // header("Location: user_edit.php");
    } else {
        $error_message ="มีบางอย่างผิดพลาด! กรุณาลองใหม่อีกครั้ง";
    };
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧋ร้านน้ำจะปั่น🧋</title>
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

        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <h1>👤โปรไฟล์</h1>
                </div>

            <form method="POST">
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
                    <button type="submit" name="update_users" class="btn">💾 บันทึกการเปลี่ยนแปลง</button>
                    <a href="index.php" class="btn">🔙 กลับหน้าหลัก</a>
                </div>

            </form>
        </div>
        <?php if ($isLoggedIn): ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php } ?>
    </div>
</body>
</html>