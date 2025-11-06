<?php
include 'db_config.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $stmt = $pdo->prepare("SELECT * FROM hr_users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Kirim password lewat email (contoh sederhana)
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '2200018433@webmail.uad.ac.id';
            $mail->Password = 'rlldrzwvngyurepz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('2200018433@webmail.uad.ac.id', 'HR System');
            $mail->addAddress($user['email'], $user['username']);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password HR Portal';
            $mail->Body = "
                <h3>Hai {$user['username']},</h3>
                <p>Berikut detail login HR Anda:</p>
                <p><b>Username:</b> {$user['username']}<br>
                <b>Password:</b> {$user['password']}</p>
                <p>Segera login dan ubah password Anda jika diperlukan.</p>
            ";
            $mail->send();
            $msg = "Password telah dikirim ke email Anda.";
        } catch (Exception $e) {
            $msg = "Gagal mengirim email: {$mail->ErrorInfo}";
        }
    } else {
        $msg = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Password - HR Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">
<div class="card p-4 shadow" style="width:400px;">
  <h4 class="text-center mb-4 text-primary fw-bold">Lupa Password</h4>
  <?php if (!empty($msg)): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Masukkan Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Kirim Password ke Email</button>
    <div class="text-center mt-3">
      <a href="login.php" class="text-decoration-none">Kembali ke Login</a>
    </div>
  </form>
</div>
</body>
</html>
