<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '2200018433@webmail.uad.ac.id'; // Email perusahaan kamu
    $mail->Password   = 'rlldrzwvngyurepz'; // App password Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('2200018433@webmail.uad.ac.id', 'HR Perusahaan');
    $mail->addAddress('akudaffa8@gmail.com'); // Ganti dengan email penerima

    $mail->isHTML(true);
    $mail->Subject = 'Tes Email dari Sistem Magang';
    $mail->Body    = 'Halo, ini adalah <b>email uji coba</b> dari sistem magang perusahaan.';

    $mail->send();
    echo '✅ Email berhasil dikirim!';
} catch (Exception $e) {
    echo "❌ Email gagal dikirim. Error: {$mail->ErrorInfo}";
}
