<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok'=>false,'bookmarks'=>[]]);
  exit;
}

$user_id = $_SESSION['user_id'];

try {
  $stmt = $pdo->prepare("SELECT url, title, created_at FROM bookmarks WHERE user_id=? ORDER BY created_at DESC");
  $stmt->execute([$user_id]);
  $bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode(['ok'=>true,'bookmarks'=>$bookmarks]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage(),'bookmarks'=>[]]);
}