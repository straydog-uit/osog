<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
session_set_cookie_params([
  'path' => '/',
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start();
header('Content-Type: application/json');

require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['logged_in' => false]);
  exit;
}

try {
  $stmt = $pdo->prepare(
    "SELECT username FROM users WHERE id = ? LIMIT 1"
  );
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch();

  if (!$user) {
    echo json_encode(['logged_in' => false]);
    exit;
  }

  echo json_encode([
    'logged_in' => true,
    'username' => $user['username']
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    'logged_in' => false,
    'error' => 'db_error'
  ]);
}