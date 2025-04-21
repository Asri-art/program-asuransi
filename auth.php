<?php
session_start();
require_once 'database.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query untuk mendapatkan user berdasarkan email
    $stmt = $conn->prepare("SELECT id, nama, password FROM pengguna WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nama'];

            // Redirect ke halaman pendaftaran asuransi
            header("Location: pendaftaran_asuransi.php");
            exit;
        } else {
            // Password salah
            header("Location: login.php?error=invalid");
            exit;
        }
    } else {
        // Email tidak ditemukan
        header("Location: login.php?error=invalid");
        exit;
    }
}
?>