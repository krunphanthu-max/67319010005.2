<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'admin'){ die('Access denied'); }
require __DIR__ . '/../inc/header.php';
$stmt = $pdo->prepare('SELECT s.*, u.email FROM sellers s JOIN users u ON u.id = s.user_id WHERE s.approved = 0'); $stmt->execute(); $pending = $stmt->fetchAll();
$stmt = $pdo->prepare('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON s.id = p.seller_id ORDER BY p.created_at DESC'); $stmt->execute(); $products = $stmt->fetchAll();
?>
<h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
<h2 class="text-lg font-semibold mt-4">อนุมัติผู้ขาย</h2>
<?php foreach($pending as $p): ?>
  <div class="bg-white p-3 rounded shadow mb-2 flex justify-between">
    <div><?= esc($p['shop_name']) ?> (<?= esc($p['email']) ?>)</div>
    <div><a href="/blue/admin/approve_seller.php?id=<?= $p['id'] ?>" class="px-2 py-1 bg-red-700 text-white rounded">อนุมัติ</a></div>
  </div>
<?php endforeach; ?>
<h2 class="text-lg font-semibold mt-4">สินค้าทั้งหมด</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<?php foreach($products as $p): ?>
  <div class="bg-white p-3 rounded shadow"><div class="flex justify-between"><div class="font-semibold"><?= esc($p['title']) ?></div><div><a href="/blue/admin/edit_product.php?id=<?= $p['id'] ?>">แก้ไข</a></div></div></div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../inc/footer.php'; ?>
