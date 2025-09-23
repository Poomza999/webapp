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
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1{
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        h2{
            color: #333;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .users-image {
            max-width: 60px;
            max-height: 60px;
            border-radius: 5px;
        }
        
        .edit-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
        }
        
        .telephone_number {
            color: #28a745;
            font-weight: bold;
        }
        
        .email {
            max-width: 300px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
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