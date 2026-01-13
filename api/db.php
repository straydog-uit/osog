<?php
// initialize db connection
$configFile = __DIR__ . '/../db.conf';

if (!file_exists($configFile)) {
  exit("db.conf not found. Please run from root 'php /api/init_db.php'.\n");
}

// Parse all key=value pairs
$config = parse_ini_file($configFile, false, INI_SCANNER_TYPED);

$DB_HOST = $config['DB_HOST'] ?? null;
$DB_NAME = $config['DB_NAME'] ?? null;
$DB_USER = $config['DB_USER'] ?? null;
$DB_PASS = $config['DB_PASS'] ?? null;

try {
  $pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (PDOException $e) {
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
  exit;
}