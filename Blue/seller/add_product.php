<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'seller'){ die('Access denied'); }
$stmt = $pdo->prepare('SELECT * FROM sellers WHERE user_id = ?'); $stmt->execute([$user['id']]); $seller = $stmt->fetch();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = 'assets/img/no-image.png';
    $published = isset($_POST['published']) ? 1 : 0;
    $stmt = $pdo->prepare('INSERT INTO products (seller_id,title,description,price,stock,image,published) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([$seller['id'],$title,$desc,$price,$stock,$image,$published]);
    header('Location: /blue/seller/dashboard.php');
    exit;
}
require __DIR__ . '/../inc/header.php';
?>
<h1 class="text-xl font-bold mb-4">เพิ่มสินค้า</h1>
<form method="post" class="bg-white p-4 rounded shadow max-w-lg">
  <label class="block mb-2">ชื่อสินค้า <input name="title" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2">รายละเอียด <textarea name="description" class="w-full border rounded px-2 py-1"></textarea></label>
  <label class="block mb-2">ราคา <input name="price" type="number" step="0.01" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2">สต็อก <input name="stock" type="number" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2"><input type="checkbox" name="published"> เผยแพร่ (ต้องรออนุมัติจาก admin)</label>
  <button class="px-4 py-2 bg-red-700 text-white rounded">บันทึก</button>
</form>
<?php require __DIR__ . '/../inc/footer.php'; ?>
