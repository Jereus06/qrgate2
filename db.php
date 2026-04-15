<?php
date_default_timezone_set('Asia/Manila');

$database_url = getenv('DATABASE_URL');
if ($database_url) {
    $db_parts = parse_url($database_url);
    $host = $db_parts['host'];
    $port = $db_parts['port'] ?? 5432;
    $user = $db_parts['user'];
    $password = $db_parts['pass'];
    $database = ltrim($db_parts['path'], '/');
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    try {
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("SET time zone '+08:00'");
    } catch (PDOException $e) {
        exit('DB connection failed: ' . $e->getMessage());
    }
} else {
    // Fallback for local dev
    $host = getenv('MYSQLHOST') ?: 'localhost';
    $user = getenv('MYSQLUSER') ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: '';
    $database = getenv('MYSQLDATABASE') ?: 'qrgate_db';
    $port = getenv('MYSQLPORT') ?: 3306;
    
    $mysqli = new mysqli($host, $user, $password, $database, $port);
    if ($mysqli->connect_errno) {
        exit('DB connection failed: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
    $mysqli->query("SET time_zone = '+08:00'");
}
?>
