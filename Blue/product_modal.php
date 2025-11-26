<?php
require 'inc/functions.php';
if(!isset($_GET['id'])) exit;
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON s.id = p.seller_id WHERE p.id = ?');
$stmt->execute([$id]);
$p = $stmt->fetch();
if(!$p) { echo 'ไม่พบสินค้า'; exit; }
?>
<div id="modal-<?php echo $p['id']; ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded shadow p-6 max-w-2xl w-full relative">
    <button onclick="document.getElementById('modal-<?php echo $p['id']; ?>').remove()" class="absolute right-4 top-4">ปิด</button>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <img src="<?php echo esc($p['image']); ?>" class="w-full h-64 object-cover rounded">
      <div>
        <h2 class="text-xl font-bold"><?php echo esc($p['title']); ?></h2>
        <p class="text-sm text-gray-600">ร้าน: <?php echo esc($p['shop_name']); ?></p>
        <p class="mt-2"><?php echo nl2br(esc($p['description'])); ?></p>
        <div class="mt-4 font-bold text-lg"><?php echo number_format($p['price'],2); ?> ฿</div>
        <form method="post" action="/blue/cart.php" class="mt-4">
          <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
          <label>จำนวน: <input type="number" name="quantity" value="1" min="1" class="border rounded px-2 py-1 w-20"></label>
          <button type="submit" class="ml-2 px-3 py-1 bg-red-700 text-white rounded">เพิ่มลงตะกร้า</button>
        </form>
      </div>
    </div>
  </div>
</div>
