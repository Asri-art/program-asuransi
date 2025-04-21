<?php
session_start();
require_once 'database.php'; // koneksi $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $error = "";

    // Validasi input
    if (strlen($password) < 8) {
        $error = "Password harus minimal 8 karakter.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM pengguna WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Hash password dan simpan
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO pengguna (nama, email, password) VALUES (?, ?, ?)");
            $ins->bind_param("sss", $nama, $email, $hash);
            if ($ins->execute()) {
                $_SESSION['user'] = $ins->insert_id;
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal daftar, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2>Silahkan Isi Data Anda</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="post" action="">
    <div class="mb-3 col-md-6">
      <label class="form-label">Nama</label>
      <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
    </div>
    <div class="mb-3 col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
    </div>
    <div class="mb-3 col-md-6">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
    </div>
    <div class="mb-3 col-md-6">
      <label class="form-label">Konfirmasi Password</label>
      <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
    </div>
    <button type="submit" class="btn btn-success">Daftar</button>
    <a href="login.php" class="btn btn-link">Sudah punya akun? Login</a>
  </form>
</body>
</html>