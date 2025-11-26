<?php
require 'inc/header.php';
$stmt = $pdo->prepare('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON s.id = p.seller_id WHERE p.published = 1 AND s.approved = 1 ORDER BY p.created_at DESC');
$stmt->execute();
$products = $stmt->fetchAll();
?>
<h1 class="text-2xl font-bold mb-4">สินค้าแนะนำ</h1>
<?php if(!$products): ?>
  <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">ยังไม่มีสินค้า <strong></strong> </div>
<?php endif; ?>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
<?php foreach($products as $p): ?>
  <div class="bg-white rounded shadow p-4">
    <img src="<?php echo esc($p['image']); ?>" class="w-full h-48 object-cover rounded">
    <h3 class="mt-2 font-semibold"><?php echo esc($p['title']); ?></h3>
    <p class="text-sm text-gray-600">ร้าน: <?php echo esc($p['shop_name']); ?></p>
    <div class="flex justify-between items-center mt-2">
      <div class="font-bold"><?php echo number_format($p['price'],2); ?> ฿</div>
      <div>
        <button onclick="openQuickView(<?php echo $p['id']; ?>)" class="px-3 py-1 bg-red-700 text-white rounded">ดูด่วน</button>
        <form method="post" action="/blue/cart.php" class="inline">
          <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
          <input type="hidden" name="quantity" value="1">
          <button class="ml-2 px-3 py-1 bg-green-600 text-white rounded">เพิ่มลงตะกร้า</button>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php require 'inc/footer.php'; ?>
