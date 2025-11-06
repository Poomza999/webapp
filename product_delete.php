<?php
require_once("./conn.php");

$success_message = '';
$error_message = '';

// ตรวจสอบว่ามี product_id ส่งมาหรือไม่
if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // ดึงข้อมูลสินค้าก่อนลบ เพื่อเอาชื่อไฟล์รูปภาพมาลบ
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        // ลบรูปภาพถ้ามี
        if (!empty($product['picture_url']) && file_exists($product['picture_url'])) {
            if (unlink($product['picture_url'])) {
                // ลบไฟล์สำเร็จ
            } else {
                $error_message = "ไม่สามารถลบไฟล์รูปภาพได้";
            }
        }
        
        // ลบข้อมูลสินค้าจากฐานข้อมูล
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        
        if ($stmt->execute()) {
            $success_message = "ลบสินค้าสำเร็จ!";
            // รอ 2 วินาทีแล้วกลับไปหน้า product_edit.php
            header("refresh:2;url=product_edit.php");
        } else {
            $error_message = "เกิดข้อผิดพลาดในการลบสินค้า: " . $conn->error;
        }
    } else {
        $error_message = "ไม่พบสินค้าที่ต้องการลบ";
    }
} else {
    $error_message = "ไม่พบรหัสสินค้าที่ต้องการลบ";
    header("refresh:2;url=product_edit.php");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบสินค้า</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .loading {
            margin-top: 20px;
            color: #666;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success_message): ?>
            <div class="icon">✅</div>
            <h1>สำเร็จ!</h1>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
            <div class="loading">
                <div class="spinner"></div>
                <p>กำลังกลับไปหน้าจัดการสินค้า...</p>
            </div>
        <?php elseif ($error_message): ?>
            <div class="icon">❌</div>
            <h1>เกิดข้อผิดพลาด!</h1>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
            <a href="product_edit.php" class="btn btn-primary">🔙 กลับไปหน้าจัดการสินค้า</a>
        <?php endif; ?>
    </div>
</body>
</html>