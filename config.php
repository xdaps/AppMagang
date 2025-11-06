<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "appmagang";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
