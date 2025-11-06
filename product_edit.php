<?php
require_once("./conn.php");

$success_message;
$error_message;

// ฟังก์ชันอัปโหลดรูปภาพ
function uploadImage($file) {
    $target_dir = "uploads/"; // โฟลเดอร์สำหรับเก็บรูปภาพ
    
    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // ตรวจสอบว่าเป็นไฟล์รูปภาพจริง
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["success" => false, "message" => "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ"];
    }
    
    // ตรวจสอบขนาดไฟล์ (จำกัดไว้ที่ 5MB)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "ขนาดไฟล์ใหญ่เกินไป (สูงสุด 5MB)"];
    }
    
    // อนุญาตเฉพาะไฟล์รูปภาพบางประเภท
    $allowed_types = array("jpg", "jpeg", "png", "gif", "webp");
    if(!in_array($imageFileType, $allowed_types)) {
        return ["success" => false, "message" => "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG, GIF และ WEBP เท่านั้น"];
    }
    
    // อัปโหลดไฟล์
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $target_file];
    } else {
        return ["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปโหลดไฟล์"];
    }
}

// ฟังก์ชันลบรูปภาพเก่า
function deleteOldImage($image_path) {
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }
}

// อัปเดตสินค้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST["price"];
    
    // ดึงรูปภาพเก่า
    $stmt = $conn->prepare("SELECT picture_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $old_data = $result->fetch_assoc();
    $picture_url = $old_data['picture_url'];
    
    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
        $upload_result = uploadImage($_FILES["picture"]);
        
        if ($upload_result["success"]) {
            // ลบรูปภาพเก่า
            deleteOldImage($old_data['picture_url']);
            $picture_url = $upload_result["filename"];
        } else {
            $error_message = $upload_result["message"];
        }
    }
    
    if (!isset($error_message)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, picture_url = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $picture_url, $id);
        
        if ($stmt->execute()) {
            $success_message = "แก้ไขข้อมูลสำเร็จ!";
        } else {
            $error_message = "มีบางอย่างผิดพลาด! กรุณาลองใหม่อีกครั้ง";
        }
    }
}

// เพิ่มสินค้าใหม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST["price"];
    $picture_url = null;
    
    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
        $upload_result = uploadImage($_FILES["picture"]);
        
        if ($upload_result["success"]) {
            $picture_url = $upload_result["filename"];
        } else {
            $error_message = $upload_result["message"];
        }
    }
    
    if (!isset($error_message)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, picture_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $picture_url);
        
        if ($stmt->execute()) {
            $success_message = "เพิ่มสินค้าสำเร็จ!";
        } else {
            $error_message = "มีบางอย่างผิดพลาด! กรุณาลองใหม่อีกครั้ง";
        }
    }
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
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        h2 {
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
        
        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
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
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
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
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
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
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
            object-fit: cover;
        }
        
        .edit-form,
        .add-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
        }
        
        .add-form {
            border-left-color: #28a745;
        }
        
        .price {
            color: #28a745;
            font-weight: bold;
        }
        
        .description {
            max-width: 300px;
            word-wrap: break-word;
        }
        
        .current-image {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        
        .current-image img {
            max-width: 200px;
            border-radius: 5px;
            border: 2px solid #ddd;
        }
        
        .image-preview {
            margin-top: 10px;
            display: none;
        }
        
        .image-preview img {
            max-width: 200px;
            border-radius: 5px;
            border: 2px solid #007bff;
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
        
        <!-- ฟอร์มแก้ไขสินค้า -->
        <?php
        if (!empty($product_id)) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row) {
        ?>
        <div class="edit-form">
            <h2>✏️ แก้ไขข้อมูลสินค้า</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                
                <div class="form-group">
                    <label for="name">ชื่อสินค้า:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($row["name"]) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">รายละเอียดสินค้า:</label>
                    <textarea id="description" name="description" rows="4"><?= htmlspecialchars($row["description"]) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">ราคา (บาท):</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= $row["price"] ?>" required>
                </div>

                <div class="form-group">
                    <label for="picture">รูปภาพสินค้า:</label>
                    <?php if (!empty($row["picture_url"]) && file_exists($row["picture_url"])): ?>
                        <div class="current-image">
                            <p><strong>รูปภาพปัจจุบัน:</strong></p>
                            <img src="<?= htmlspecialchars($row["picture_url"]) ?>" alt="Current Product Image">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="picture" name="picture" accept="image/*" onchange="previewImage(this, 'edit-preview')">
                    <div id="edit-preview" class="image-preview">
                        <p><strong>รูปภาพใหม่:</strong></p>
                        <img src="" alt="Preview">
                    </div>
                    <small style="color: #666;">อัปโหลดรูปภาพใหม่หากต้องการเปลี่ยน (JPG, PNG, GIF, WEBP - สูงสุด 5MB)</small>
                </div>

                <button type="submit" name="update_product" class="btn btn-primary">💾 บันทึกการแก้ไข</button>
                <a href="product_edit.php" class="btn btn-secondary">❌ ยกเลิก</a>
            </form>
        </div>
        <?php 
            }
        } else {
        ?>
        <!-- ฟอร์มเพิ่มสินค้าใหม่ -->
        <div class="add-form">
            <h2>➕ เพิ่มสินค้าใหม่</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="add_name">ชื่อสินค้า:</label>
                    <input type="text" id="add_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="add_description">รายละเอียดสินค้า:</label>
                    <textarea id="add_description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="add_price">ราคา (บาท):</label>
                    <input type="number" id="add_price" name="price" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="add_picture">รูปภาพสินค้า:</label>
                    <input type="file" id="add_picture" name="picture" accept="image/*" onchange="previewImage(this, 'add-preview')">
                    <div id="add-preview" class="image-preview">
                        <p><strong>ตัวอย่างรูปภาพ:</strong></p>
                        <img src="" alt="Preview">
                    </div>
                    <small style="color: #666;">JPG, PNG, GIF, WEBP - สูงสุด 5MB</small>
                </div>

                <button type="submit" name="add_product" class="btn btn-success">📦 เพิ่มสินค้า</button>
            </form>
        </div>
        <?php } ?>

        <!-- รายการสินค้าทั้งหมด -->
        <h2>📋 รายการสินค้า</h2>
        
        <?php 
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
        ?>
            <p>ไม่มีสินค้าในระบบ</p>
        <?php
        } else {
        ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>รูปภาพ</th>
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
                    <td>
                        <?php if (!empty($product['picture_url']) && file_exists($product['picture_url'])): ?>
                            <img src="<?php echo htmlspecialchars($product['picture_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="product-image">
                        <?php else: ?>
                            <span style="color: #999;">ไม่มีรูปภาพ</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                    <td class="description"><?php echo htmlspecialchars($product['description'] ?? 'ไม่มีรายละเอียด'); ?></td>
                    <td class="price">
                        <?php echo $product['price'] ? number_format($product['price'], 2) . ' บาท' : 'ไม่ระบุราคา'; ?>
                    </td>
                    <td>
                        <a href="?product_id=<?php echo $product['id']; ?>" class="btn btn-warning">✏️ แก้ไข</a>
                        <a href="product_delete.php?product_id=<?php echo $product['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">🗑️ ลบ</a>
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

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>