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
    <style>
        /* style_edit.css */
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
        .btn-add {
            background-color: #1eff00;
            color: rgb(0, 0, 0);
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
        
        .product-image {
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
        
        .price {
            color: #28a745;
            font-weight: bold;
        }
        
        .description {
            max-width: 300px;
            word-wrap: break-word;
        }
    </style>
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
        <div class="add-form">
            <button type="submit" name="add_product" class="btn btn">📦 เพิ่มสินค้า</button>
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

                <button type="submit" name="update_product" class="btn btn-primary">📦 เพิ่มสินค้า</button>
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
                        <a href="product_delete.php?product_id=<?php echo $product['id']; ?>" class="btn btn-secondary" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">🗑️ ลบ</a>
                    </td>
                    <td></td>
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