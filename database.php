<?php
$host = 'localhost';
$user = 'root';          // Username database kamu
$pass = '';              // Password database (kosongkan jika tidak ada)
$db   = 'db_asuransi';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi database gagal: " . $conn->connect_error);
}
?>
