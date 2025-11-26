<?php
require 'inc/functions.php';
require_login();
require 'inc/header.php';
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>
<h1 class="text-xl font-bold mb-4">ประวัติคำสั่งซื้อ</h1>
<?php if(!$orders): ?><div>ยังไม่มีคำสั่งซื้อ</div><?php else: ?>
  <?php foreach($orders as $o): ?>
    <div class="bg-white p-4 rounded shadow mb-4">
      <div class="flex justify-between"><div>คำสั่งซื้อ #<?= $o['id'] ?></div><div><?= $o['created_at'] ?></div></div>
      <div>สถานะ: <?= esc($o['status']) ?></div>
      <div class="mt-2">
      <?php
      $stmt = $pdo->prepare('SELECT oi.*, p.title FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
      $stmt->execute([$o['id']]); $items = $stmt->fetchAll();
      foreach($items as $it){ echo '<div class="border-t py-2">'.esc($it['title']).' x '.esc($it['quantity']).' - '.number_format($it['price'],2).' ฿</div>'; }
      ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; require 'inc/footer.php'; ?>
