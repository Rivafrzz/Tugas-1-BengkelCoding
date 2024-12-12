<?php
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "bk_poliklinik_2024"; // Nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
try {
    $host = 'localhost';
    $db = 'bk_poliklinik_2024'; // Nama database Anda
    $user = 'root';             // Username default
    $pass = '';                 // Password default

    // Buat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
