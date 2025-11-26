<?php
require_once __DIR__ . '/../inc/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'admin'){ die('Access denied'); }
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('UPDATE sellers SET approved = 1 WHERE id = ?'); $stmt->execute([$id]);
$stmt = $pdo->prepare('UPDATE products SET published = 1 WHERE seller_id = ?'); $stmt->execute([$id]);
header('Location: /blue/admin/dashboard.php'); exit;
