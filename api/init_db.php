<?php
function prompt($msg, $hide = false) {
  echo $msg;
  if ($hide) {
    system('stty -echo');
    $v = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    return $v;
  }
  return trim(fgets(STDIN));
}

echo "==== LOCAL DATABASE INITIALIZATION (MySQL Server) ====\n";

// Credentials
$mysqlUser = prompt("User: ");
$mysqlPass = prompt("Password: ", true);

// Connect
try {
  $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", $mysqlUser, $mysqlPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
} catch (PDOException $e) {
  exit("Unable to connect to MySQL: " . $e->getMessage() . "\n");
}

echo "\n1) Create a new database\n";
echo "2) Import database from a .sql file\n";
while (true) {
  $mode = prompt("Select [1/2]: ");
  if ($mode === "1" || $mode === "2") break;
}

// MODE 1: Create New Database
if ($mode === "1") {

  while (true) {
    $dbName = prompt("Database name: ");
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->fetch()) {
      echo "Database already exists, please enter again.\n";
    } else break;
  }

  try {
    $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbName' created.\n";

    $pdo = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8mb4", $mysqlUser, $mysqlPass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $schemaFile = __DIR__ . '/../db/schema.sql';
    if (!file_exists($schemaFile)) {
      throw new Exception("schema.sql not found at $schemaFile");
    }

    $sql = file_get_contents($schemaFile);
    $pdo->exec($sql);

    echo "Database initialized.\n";

  } catch (Throwable $e) {
    exit("Initialization failed: " . $e->getMessage() . "\n");
  }
}

// MODE 2: Import Existing SQL File
if ($mode === "2") {

  while (true) {
    $dbName = prompt("Database name to import into: ");
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if (!$stmt->fetch()) {
      echo "Database does not exist, please enter again.\n";
    } else break;
  }

  while (true) {
    $sqlFile = prompt("Path to .sql file: ");
    if (file_exists($sqlFile)) break;
    echo "File not found → please enter again.\n";
  }

  echo "Importing data from SQL file into database '$dbName'...\n";

  // Force import into the specified database even if .sql contains USE statements
  $cmd = "mysql -u$mysqlUser -p$mysqlPass --database=" .
  escapeshellarg($dbName) .
  " --one-database < " .
  escapeshellarg($sqlFile);
  system($cmd);
}

file_put_contents(__DIR__ . "/../db.conf",
"DB_HOST=localhost
DB_NAME=$dbName
DB_USER=$mysqlUser
DB_PASS=$mysqlPass
");

echo "\n==== DONE ====\n";
echo "Database '$dbName' is ready\n";
echo "Created/imported by user: '$mysqlUser'\n";