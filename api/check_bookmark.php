<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'saved' => false]);
  exit;
}

$user_id = $_SESSION['user_id'];
$post = $_GET['post'] ?? '';

if (!$post) {
  echo json_encode(['ok' => false, 'saved' => false]);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE user_id=? AND url=? LIMIT 1");
  $stmt->execute([$user_id, $post]);
  $row = $stmt->fetch();
  echo json_encode(['ok' => true, 'saved' => (bool)$row]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}