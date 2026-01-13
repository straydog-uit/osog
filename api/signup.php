<?php
session_start();
require 'db.php'; // $pdo

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  echo json_encode(['ok' => false, 'error' => 'Missing required fields']);
  exit;
}

// check username
$stmt = $pdo->prepare("SELECT 1 FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
  echo json_encode(['ok' => false, 'field' => 'username', 'error' => 'Username already exists']);
  exit;
}

// check email (optional)
if ($email) {
  $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
  $stmt->execute([$email]);
  if ($stmt->fetch()) {
    echo json_encode(['ok' => false, 'field' => 'email', 'error' => 'Email already exists']);
    exit;
  }
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
  "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)"
);
$stmt->execute([$username, $email ?: null, $hash]);

echo json_encode(['ok' => true]);