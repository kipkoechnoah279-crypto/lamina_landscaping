<?php
$host = getenv('DB_HOST') ?: 'sql7.freesqldatabase.com';
$db   = getenv('DB_NAME') ?: 'sql7840058';
$user = getenv('DB_USER') ?: 'sql7840058';
$pass = getenv('DB_PASS') ?: 'T8iVkVNASV';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please check configuration settings.");
}
?>