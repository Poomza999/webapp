<?php
require_once("./conn.php");

$success_message;
$error_message;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST["price"];

    // i int
    // s string, text
    // d double, float
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $name, $description, $price, $id);
    if ($stmt->execute()) {
        $success_message = "แก้ไขข้อมูลสำเร็จ!";
        // header("Location: product_edit.php");
    } else {
        $error_message ="มีบางอย่างผิดพลาด! กรุณาลองใหม่อีกครั้ง";
    };
}

$product_id = isset($_GET["product_id"]) ? $_GET["product_id"] : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า - แก้ไขข้อมูล</title>
    <link rel="stylesheet" href="style_edit.css">
    
</head>
<body>
    <div class="container">
        <h1>🛍️ จัดการสินค้า</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmt->bind_param("s", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
        ?>
        <div class="edit-form">
            <h2>✏️ แก้ไขข้อมูลสินค้า</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                
                <div class="form-group">
                    <label for="name">ชื่อสินค้า:</label>
                    <input type="text" id="name" name="name" value="<?= $row["name"] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">รายละเอียดสินค้า:</label>
                    <textarea id="description" name="description" rows="4"><?= $row["description"] ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input id="price" name="price" rows="4" value="<?= $row["price"] ?>">
                </div>

                <button type="submit" name="update_product" class="btn btn-primary">💾 บันทึกการแก้ไข</button>
                <a href="product_edit.php" class="btn btn-secondary">❌ ยกเลิก</a>
            </form>
        </div>
        <?php } ?>
        
        <h2>📋 รายการสินค้า</h2>
        
        <?php 
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
    
        $result = $stmt->get_result();
        if (!$result->num_rows > 0) {
        ?>
            <p>ไม่มีสินค้าในระบบ</p>
        <?php
        } else {
        ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อสินค้า</th>
                        <th>รายละเอียด</th>
                        <th>ราคา</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($product = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                    <td class="description"><?php echo htmlspecialchars($product['description'] ?? 'ไม่มีรายละเอียด'); ?></td>
                    <td class="price">
                        <?php echo $product['price'] ? number_format($product['price'], 2) . ' บาท' : 'ไม่ระบุราคา'; ?>
                    </td>
                    <td>
                        <a href="?product_id=<?php echo $product['id']; ?>" class="btn btn-warning">✏️ แก้ไข</a>
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