<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'seller'){ die('Access denied'); }
$stmt = $pdo->prepare('SELECT * FROM sellers WHERE user_id = ?'); $stmt->execute([$user['id']]); $seller = $stmt->fetch();
if(!$seller) die('ไม่พบร้านค้า');
$stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id = ?'); $stmt->execute([$seller['id']]); $products = $stmt->fetchAll();
require __DIR__ . '/../inc/header.php';
?>
<h1 class="text-2xl font-bold mb-4">Seller Dashboard - <?= esc($seller['shop_name']) ?></h1>
<a href="/blue/seller/add_product.php" class="px-3 py-1 bg-red-700 text-white rounded">เพิ่มสินค้า</a>
<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
<?php foreach($products as $p): ?>
  <div class="bg-white p-4 rounded shadow">
    <div class="font-semibold"><?= esc($p['title']) ?></div>
    <div class="text-sm"><?= number_format($p['price'],2) ?> ฿ - สต็อก <?= esc($p['stock']) ?></div>
  </div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../inc/footer.php'; ?>
