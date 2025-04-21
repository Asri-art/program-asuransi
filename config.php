<?php
// Konfigurasi koneksi database
$host = 'localhost'; // Host database, biasanya 'localhost'
$username = 'root';  // Nama pengguna database
$password = '';      // Kata sandi database (kosong jika menggunakan XAMPP default)
$database = 'sistem_asuransi'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>