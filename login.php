<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <div class="card col-md-6 offset-md-3">
    <div class="card-body">
      <h3 class="card-title mb-3">Login User</h3>
      <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
        <div class="alert alert-danger">Email atau password salah.</div>
      <?php endif; ?>
      <form action="auth.php" method="POST">
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
</body>
</html>