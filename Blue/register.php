<?php
require 'inc/functions.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = in_array($_POST['role'] ?? 'user', ['user','seller']) ? $_POST['role'] : 'user';
    if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 4){
        $errors[] = 'กรอกข้อมูลให้ถูกต้อง';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
        try {
            $stmt->execute([$name,$email,$hash,$role]);
            $user_id = $pdo->lastInsertId();
            if($role === 'seller'){
                $stmt2 = $pdo->prepare('INSERT INTO sellers (user_id,shop_name,approved) VALUES (?,?,0)');
                $stmt2->execute([$user_id, $name . ' shop']);
            }
            header('Location: /blue/login.php');
            exit;
        } catch (Exception $e){
            $errors[] = 'ไม่สามารถลงทะเบียน: ' . $e->getMessage();
        }
    }
}
require 'inc/header.php';
?>
<h1 class="text-xl font-bold mb-4">สมัครสมาชิก</h1>
<?php foreach($errors as $err): ?>
  <div class="text-red-600 mb-2"><?php echo esc($err); ?></div>
<?php endforeach; ?>
<form method="post" class="bg-white p-4 rounded shadow max-w-md">
  <label class="block mb-2">ชื่อ <input name="name" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2">อีเมล <input name="email" type="email" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2">รหัสผ่าน <input name="password" type="password" class="w-full border rounded px-2 py-1"></label>
  <label class="block mb-2">ลงทะเบียนในฐานะ:
    <select name="role" class="w-full border rounded px-2 py-1">
      <option value="user">ผู้ใช้</option>
      <option value="seller">ผู้ขาย</option>
    </select>
  </label>
  <button class="px-4 py-2 bg-red-700 text-white rounded">สมัคร</button>
</form>
<?php require 'inc/footer.php'; ?>
