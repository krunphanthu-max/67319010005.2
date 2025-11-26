<?php
require_once __DIR__ . '/config.php';
function is_logged_in(){ return isset($_SESSION['user_id']); }
function current_user(){
    global $pdo;
    if(!is_logged_in()) return null;
    $stmt = $pdo->prepare("SELECT id,name,email,role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
function require_login(){ if(!is_logged_in()){ header('Location: /blue/login.php'); exit; } }
function esc($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
