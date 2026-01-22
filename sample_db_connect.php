<?php
$host = "localhost";      // or your hosting MySQL server
$db   = "";     // database name
$user = "";           // database username
$pass = "";               // database password

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);

    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
