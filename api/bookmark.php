<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok'=>false,'error'=>'not_logged_in']);
  exit;
}

$user_id = $_SESSION['user_id'];

$post  = $_POST['post'] ?? null;
$title = $_POST['title'] ?? null;

if (!$post || !$title) {
  $input = json_decode(file_get_contents('php://input'), true);
  $post  = $post ?? $input['post'] ?? '';
  $title = $title ?? $input['title'] ?? $post;
}

if (!$post) {
  echo json_encode(['ok'=>false,'error'=>'missing_post']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE user_id=? AND url=? LIMIT 1");
  $stmt->execute([$user_id, $post]);
  $row = $stmt->fetch();

  if ($row) {
    $del = $pdo->prepare("DELETE FROM bookmarks WHERE id=?");
    $del->execute([$row['id']]);
    echo json_encode(['ok'=>true,'saved'=>false]);
  } else {
    $ins = $pdo->prepare("INSERT INTO bookmarks (user_id, url, title, created_at) VALUES (?, ?, ?, NOW())");
    $ins->execute([$user_id, $post, $title]);
    echo json_encode(['ok'=>true,'saved'=>true]);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}