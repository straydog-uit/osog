<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['ok' => false, 'error' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];

$old_pass = $_POST['old_password'] ?? '';
$new_pass = $_POST['new_password'] ?? '';

if ($old_pass === '' || $new_pass === '') {
  echo json_encode(['ok' => false, 'error' => 'Missing fields']);
  exit;
}

$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
  echo json_encode(['ok' => false, 'error' => 'User not found']);
  exit;
}

if (!password_verify($old_pass, $user['password_hash'])) {
  echo json_encode(['ok' => false, 'error' => 'Old password wrong']);
  exit;
}

$new_hash = password_hash($new_pass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt->execute([$new_hash, $user_id]);

echo json_encode(['ok' => true]);