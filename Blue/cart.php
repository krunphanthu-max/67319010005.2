<?php
require 'inc/functions.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require_login();
    $product_id = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    $stmt = $pdo->prepare('SELECT id FROM cart WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$_SESSION['user_id'],$product_id]);
    $row = $stmt->fetch();
    if($row){
        $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity + ? WHERE id = ?');
        $stmt->execute([$qty, $row['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO cart (user_id,product_id,quantity) VALUES (?,?,?)');
        $stmt->execute([$_SESSION['user_id'],$product_id,$qty]);
    }
    header('Location: /blue/cart.php');
    exit;
}
require_login();
require 'inc/header.php';
$stmt = $pdo->prepare('SELECT c.*, p.title, p.price FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();
$total = 0; foreach($items as $it) $total += $it['price'] * $it['quantity'];
?>
<h1 class="text-xl font-bold mb-4">ตะกร้าสินค้า</h1>
<?php if(!$items): ?><div>ตะกร้าว่าง</div><?php else: ?>
<div class="bg-white p-4 rounded shadow">
<?php foreach($items as $it): ?>
  <div class="flex justify-between border-b py-2">
    <div><div class="font-semibold"><?php echo esc($it['title']); ?></div><div class="text-sm"><?php echo esc($it['quantity']); ?> x <?php echo number_format($it['price'],2); ?> ฿</div></div>
    <div><?php echo number_format($it['price'] * $it['quantity'],2); ?> ฿</div>
  </div>
<?php endforeach; ?>
<div class="text-right font-bold mt-4">รวมทั้งสิ้น: <?php echo number_format($total,2); ?> ฿</div>
<form method="post" action="/blue/checkout.php" class="text-right mt-4"><button class="px-4 py-2 bg-red-700 text-white rounded">ไปชำระเงิน</button></form>
</div>
<?php endif; require 'inc/footer.php'; ?>
