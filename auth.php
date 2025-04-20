<!-- auth.php -->
<?php
session_start();
require 'database.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM pengguna WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user['id'];
    header('Location: index.php');
    exit;
  } else {
    echo "Password salah.";
  }
} else {
  echo "Email tidak ditemukan.";
}
?>
