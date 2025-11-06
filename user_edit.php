<?php
require_once("./conn.php");

$success_message;
$error_message;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_users"])) {
    $id = $_POST['id'];
    $username = $_POST['userusername'];
    $email = $_POST['email'];
    $telephone_number = $_POST["telephone_number"];

    // i int
    // s string, text
    // d double, float
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, telephone_number = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $telephone_number, $id);
    if ($stmt->execute()) {
        $success_message = "แก้ไขข้อมูลสำเร็จ!";
        // header("Location: user_edit.php");
    } else {
        $error_message ="มีบางอย่างผิดพลาด! กรุณาลองใหม่อีกครั้ง";
    };
}

$users_id = isset($_GET["users_id"]) ? $_GET["users_id"] : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta username="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้งาน - แก้ไขข้อมูล</title>
    <link rel="stylesheet" href="style_edit.css">

</head>
<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="index_admin.php" class="btn btn-secondary">🏠 Back To Dashboard</a>
        </div>
        <h1>👨‍🔧 จัดการผู้ใช้งาน</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->bind_param("s", $users_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
        ?>
        <div class="edit-form">
            <h2>✏️ แก้ไขข้อมูลผู้ใช้งาน</h2>
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

                    <button type="submit" name="update_users" class="btn btn-primary">💾 บันทึกการแก้ไข</button>
                    <a href="user_edit.php" class="btn btn-secondary">❌ ยกเลิก</a>
            </form>
        </div>
        <?php } ?>

        <h2>📋 รายการผู้ใช้งาน</h2>

        <?php
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
    
        $result = $stmt->get_result();
        if (!$result->num_rows > 0) {
        ?>
            <p>ไม่มีผู้ใช้งานในระบบ</p>
        <?php
        } else {
        ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้งาน</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($users = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $users['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($users['username']); ?></strong></td>
                    <td class="email"><?php echo htmlspecialchars($users['email'] ?? 'ไม่มีรายละเอียด'); ?></td>
                    <td class="telephone_number">
                        <?php echo $users['telephone_number'] ? htmlspecialchars($users['telephone_number']) : 'ไม่ระบุเบอร์โทร'; ?>
                    </td>
                    <td>
                        <a href="?users_id=<?php echo $users['id']; ?>" class="btn btn-warning">✏️ แก้ไข</a>
                    </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
</body>
</html>