<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $universitas = $_POST['universitas'];
    $jurusan = $_POST['jurusan'];
    $posisi = $_POST['posisi'];

    $cv = $_FILES['cv']['name'];
    $target = "uploads/" . basename($cv);
    move_uploaded_file($_FILES['cv']['tmp_name'], $target);

    $stmt = $pdo->prepare("INSERT INTO interns (nama, email, no_hp, universitas, jurusan, posisi, cv) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $email, $no_hp, $universitas, $jurusan, $posisi, $cv]);

    echo "<script>alert('Pendaftaran berhasil dikirim!');window.location='index.php';</script>";
}
