<?php
$host = getenv('DB_HOST') ?: 'sql7.freesqldatabase.com';
$db   = getenv('DB_NAME') ?: 'sql7840058';
$user = getenv('DB_USER') ?: 'sql7840058';
$pass = getenv('DB_PASS') ?: 'T8iVkVNASV';
$port = getenv('DB_PORT') ?: '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log("Database Connection Error (Host: $host): " . $e->getMessage());
    die("Database connection failed. Please check if your database host ($host) is active and reachable.");
}
?>