<?php
// --- Aktifkan error (debugging) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Ambil data dari POST (dikirim dari tombol dashboard) ---
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = trim($_POST['status']);

    // --- Ambil data peserta ---
    $stmt = $pdo->prepare("SELECT nama, email FROM peserta_magang WHERE id = ?");
    $stmt->execute([$id]);
    $peserta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($peserta) {
        // --- Update status di database ---
        $update = $pdo->prepare("UPDATE peserta_magang SET status = ? WHERE id = ?");
        $update->execute([$status, $id]);

        // --- Kirim email otomatis ---
        $mail = new PHPMailer(true);

        try {
            // --- Konfigurasi SMTP Gmail ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '2200018433@webmail.uad.ac.id'; // ganti dengan email kamu
            $mail->Password   = 'rlldrzwvngyurepz';             // ganti dengan App Password Gmail kamu
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // --- Pengirim dan penerima ---
            $mail->setFrom('2200018433@webmail.uad.ac.id', 'HR Perusahaan');
            $mail->addAddress($peserta['email'], $peserta['nama']);

            // --- Tentukan isi email berdasarkan status ---
            if (strtolower($status) === 'diterima') {
                $subject = "Selamat! Anda Diterima untuk Magang di Perusahaan Kami ";
                $body = "
                    <h3>Hai {$peserta['nama']},</h3>
                    <p>Selamat! Anda telah <b>DITERIMA</b> untuk posisi magang di perusahaan kami 🎉</p>
                    <p>Kami akan segera menghubungi Anda untuk proses selanjutnya.</p>
                    <p>Salam hangat,<br><b>HR Perusahaan</b></p>
                ";
            } elseif (strtolower($status) === 'ditolak') {
                $subject = "Hasil Seleksi Magang di Perusahaan Kami";
                $body = "
                    <h3>Hai {$peserta['nama']},</h3>
                    <p>Terima kasih telah mendaftar magang di perusahaan kami.</p>
                    <p>Setelah melalui proses seleksi, kami mohon maaf karena Anda <b>belum dapat diterima</b> kali ini.</p>
                    <p>Semoga sukses untuk kesempatan berikutnya!</p>
                    <p>Salam,<br><b>HR Perusahaan</b></p>
                ";
            } else {
                $subject = "Status Lamaran Anda Telah Diperbarui";
                $body = "
                    <h3>Hai {$peserta['nama']},</h3>
                    <p>Status lamaran Anda telah diperbarui menjadi: <b>$status</b>.</p>
                    <p>Terima kasih.</p>
                ";
            }

            // --- Kirim email ---
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();

        } catch (Exception $e) {
            echo "<script>alert('Status berhasil diupdate tapi email gagal dikirim: {$mail->ErrorInfo}'); window.location='dashboard.php?msg=updated';</script>";
            exit;
        }

        // --- Redirect setelah sukses ---
        header("Location: dashboard.php?msg=updated");
        exit;

    } else {
        echo "<script>alert('Data peserta tidak ditemukan'); window.location='dashboard.php';</script>";
        exit;
    }

} else {
    echo "<script>alert('Permintaan tidak valid'); window.location='dashboard.php';</script>";
    exit;
}
?>
