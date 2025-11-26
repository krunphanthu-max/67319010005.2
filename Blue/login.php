<?php
require 'inc/functions.php';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id,password,role,name FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if($u){
        if(password_verify($password, $u['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id'];
            header('Location: /blue/');
            exit;
        } else {
            $errors[] = 'รหัสผ่านไม่ถูกต้อง';
        }
    } else {
        $errors[] = 'อีเมลไม่ถูกต้อง';
    }
}

require 'inc/header.php';
?>
<h1 class="text-xl font-bold mb-4">เข้าสู่ระบบ</h1>
<?php foreach($errors as $err): ?>
  <div class="text-red-600 mb-2"><?php echo esc($err); ?></div>
<?php endforeach; ?>
<form method="post" class="bg-white p-4 rounded shadow max-w-md">
  <label class="block mb-2">อีเมล
    <input name="email" type="email" class="w-full border rounded px-2 py-1" required>
  </label>
  <label class="block mb-2">รหัสผ่าน
    <input name="password" type="password" class="w-full border rounded px-2 py-1" required>
  </label>
  <button class="px-4 py-2 bg-red-700 text-white rounded">เข้าสู่ระบบ</button>
</form>
<?php require 'inc/footer.php'; ?>
