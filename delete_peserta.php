<?php
include 'config.php';

// Pastikan hanya request dari admin yang sah (jika sudah ada sistem otentikasi)
// Anda bisa menambahkan pengecekan session admin di sini.

// 1. Pastikan request menggunakan GET dan ID tersedia
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // Ambil dan bersihkan ID (sangat penting untuk mencegah SQL Injection)
    $id_peserta = $conn->real_escape_string($_GET['id']);
    
    // Query DELETE
    $sql = "DELETE FROM peserta_magang WHERE id = '$id_peserta'";
    
    if ($conn->query($sql) === TRUE) {
        // Berhasil dihapus, alihkan kembali ke dashboard dengan pesan sukses
        $message = "deleted_success";
    } else {
        // Gagal dihapus
        $message = "deleted_fail";
    }

} else {
    // ID tidak valid atau tidak ada
    $message = "invalid_id";
}

// Alihkan kembali ke halaman dashboard dengan status pesan
header("Location: dashboard.php?msg=" . $message);
exit();

?>