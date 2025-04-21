<?php
// Konfigurasi database
$host = "localhost"; // Nama host database (default: localhost)
$username = "root"; // Username database
$password = ""; // Password database (kosong jika default)
$database = "db_asuransi"; // Nama database Anda

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Koneksi berhasil!";
?>