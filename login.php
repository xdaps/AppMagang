<?php
session_start();
include 'db_config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ambil data dari tabel hr_users
    $stmt = $pdo->prepare("SELECT * FROM hr_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Karena password di DB masih plain text (admin123)
    if ($user && $password === $user['password']) {
        $_SESSION['user'] = $user['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login HR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">
<div class="card p-4 shadow" style="width:400px;">
  <h4 class="text-center mb-4 text-primary fw-bold">Login HR Portal</h4>
  <form method="POST">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button name="login" class="btn btn-primary w-100 mb-3">Masuk</button>
    <div class="text-center">
      <a href="forgot_password.php" class="text-decoration-none">Lupa Password?</a>
    </div>
  </form>
</div>
</body>
</html>
