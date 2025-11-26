<?php
require 'inc/functions.php';
require_login();
$stmt = $pdo->prepare('SELECT c.*, p.price, p.seller_id FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();
if(!$items){ header('Location: /blue/cart.php'); exit; }
$total = 0; foreach($items as $it){ $total += $it['price'] * $it['quantity']; }
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('INSERT INTO orders (user_id,total) VALUES (?,?)');
        $stmt->execute([$_SESSION['user_id'],$total]);
        $order_id = $pdo->lastInsertId();
        $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id,product_id,seller_id,quantity,price) VALUES (?,?,?,?,?)');
        foreach($items as $it){ $stmtItem->execute([$order_id,$it['product_id'],$it['seller_id'],$it['quantity'],$it['price']]); }
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $pdo->commit();
        header('Location: /blue/orders.php');
        exit;
    } catch(Exception $e){ $pdo->rollBack(); die('Checkout failed: ' . $e->getMessage()); }
}
require 'inc/header.php';
?>
<h1 class="text-xl font-bold mb-4">ยืนยันการสั่งซื้อ</h1>
<div class="bg-white p-4 rounded shadow">
  <div>จำนวนรายการ: <?php echo count($items); ?></div>
  <div class="font-bold mt-2">รวม: <?php echo number_format($total,2); ?> ฿</div>
  <form method="post" class="mt-4"><button class="px-4 py-2 bg-red-700 text-white rounded">ยืนยันสั่งซื้อ</button></form>
</div>
<?php require 'inc/footer.php'; ?>
