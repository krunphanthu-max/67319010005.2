<?php<?php
require 'inc/config.php';

try {
    // ตรวจสอบว่าตาราง users มีหรือยัง
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $exists = $stmt->fetchColumn();

    if (!$exists) {
        // Import schema
        $sql = file_get_contents(__DIR__ . '/sql/schema.sql');
        $pdo->exec($sql);
        echo "Schema imported.\n";
    }

    // ตรวจสอบว่ามี users อยู่แล้วหรือไม่
    $stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $c = $stmt->fetchColumn();
    if ($c > 0) {
        echo "Seed already ran (users exist).\n";
        exit;
    }

    // สร้าง users
    $users = [
        ['Admin','admin@example.com','1234','admin'],
        ['Seller','seller@example.com','1234','seller'],
        ['User','user@example.com','1234','user'],
    ];
    $ins = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
    foreach($users as $u){
        $ins->execute([$u[0], $u[1], password_hash($u[2], PASSWORD_DEFAULT), $u[3]]);
    }

    // ดึง id ของ seller
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute(['seller@example.com']);
    $seller_user_id = $stmt->fetchColumn();

    if ($seller_user_id) {
        // ตรวจสอบว่าตาราง sellers มีคอลัมน์ shop_name หรือใช้ name แทน
        $stmtCheck = $pdo->query("DESCRIBE sellers");
        $columns = $stmtCheck->fetchAll(PDO::FETCH_COLUMN);
        $shop_column = in_array('shop_name', $columns) ? 'shop_name' : 'name';

        // สร้าง seller
        $pdo->prepare("INSERT INTO sellers (user_id, $shop_column, description, approved) VALUES (?,?,?,1)")
            ->execute([$seller_user_id,'Blue Shop','ร้านตัวอย่าง Blue']);
        $seller_id = $pdo->lastInsertId();

        // สร้างสินค้า 10 รายการ
        $products = [
            ['iPhone 15','iPhone 15 - 128GB',36900,10,'assets/img/iphone15.jpg'],
        ];

        $pstmt = $pdo->prepare('INSERT INTO products (seller_id,title,description,price,stock,image,published) VALUES (?,?,?,?,?,?,1)');
        foreach($products as $p){
            $pstmt->execute([$seller_id,$p[0],$p[1],$p[2],$p[3],$p[4]]);
        }
    }

    echo "Seed complete. Accounts:\n";
    echo "Admin admin@example.com / 1234\n";
    echo "Seller seller@example.com / 1234\n";
    echo "User user@example.com / 1234\n";

} catch(Exception $e){
    echo 'Error: ' . $e->getMessage();
}
