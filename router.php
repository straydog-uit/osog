<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/api/#', $uri)) {
  $path = __DIR__ . $uri;
  if (file_exists($path)) {
    include $path;
    return;
  }
  http_response_code(404);
  echo "API not found";
  return;
}

$path = $_SERVER['DOCUMENT_ROOT'] . $uri;

if (is_dir($path)) {
  $path = rtrim($path, '/') . '/index.html';
}

if (is_file($path)) {
  return false;
}

http_response_code(404);
echo 'Not found';