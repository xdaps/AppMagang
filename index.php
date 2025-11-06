<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $universitas = $_POST['asal_univ']; // ganti nama variabel, karena kolomnya 'universitas'
    $jurusan = $_POST['jurusan'];
    $durasi = $_POST['durasi'];

    // Upload file
    $cv = $_FILES['cv']['name'];
    $rekomendasi = $_FILES['rekomendasi']['name'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    move_uploaded_file($_FILES['cv']['tmp_name'], $target_dir . $cv);
    move_uploaded_file($_FILES['rekomendasi']['tmp_name'], $target_dir . $rekomendasi);

    // Simpan ke database
    $sql = "INSERT INTO peserta_magang (nama, email, no_hp, universitas, jurusan, durasi, cv, rekomendasi, tanggal_daftar)
            VALUES ('$nama', '$email', '$no_hp', '$universitas', '$jurusan', '$durasi', '$cv', '$rekomendasi', NOW())";

    $stmt = $pdo->prepare("INSERT INTO peserta_magang 
    (nama, email, no_hp, universitas, jurusan, durasi, cv, rekomendasi, tanggal_daftar)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if ($stmt->execute([$nama, $email, $no_hp, $universitas, $jurusan, $durasi, $cv, $rekomendasi])) {
    echo "<script>alert('✅ Pendaftaran berhasil dikirim!'); window.location='index.html';</script>";
} else {
    echo "<script>alert('❌ Gagal menyimpan data!');</script>";
}

}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran Magang BPJS Ketenagakerjaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Disesuaikan dengan Gaya Formulir Pendaftaran Merah Putih */
        body {
            background-color: #ffffff; 
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            /* Tambahan padding atas agar logo watermark di atas container tidak terpotong */
            padding-top: 50px; 
        }
        .container {
            max-width: 650px;
            margin-top: 10px; /* Kurangi margin atas karena sudah ada padding body */
            background: white; 
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 30px 40px;
            position: relative;
            z-index: 10;
        }
        h3 {
            color: #000000;
            font-weight: 700;
            text-align: center;
        }
        
        /* Desain Latar Belakang Khas Formulir (Merah, Putih, Abu-abu, Hitam) */
        /* Kontrol Z-Index untuk lapisan desain */
        .header-design {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1; /* Di belakang kontainer form dan watermark */
        }
        .header-design::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 250px; 
            height: 200px;
            background: 
                linear-gradient(135deg, #a0a0a0 15%, transparent 15%) 0 0,
                linear-gradient(135deg, #d3d3d3 30%, transparent 30%) -10px -10px;
            background-size: 50px 50px;
            background-repeat: repeat;
        }
        .header-design::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 250px; 
            height: 200px;
            background-color: transparent;
            background-image: 
                linear-gradient(135deg, #e4002b 50%, transparent 50%),
                linear-gradient(45deg, #e4002b 50%, transparent 50%);
            background-size: 125px 125px;
            background-position: 125px 0, 0 125px;
            background-repeat: no-repeat;
        }
        
        /* --- LOGO BPJS KETENAGAKERJAAN SEBAGAI WATERMARK --- */
        .logo-watermark {
            position: fixed; /* Fixed agar tetap di tempat meskipun di-scroll */
            top: 50px; /* Jarak dari atas */
            left: 50%;
            transform: translateX(-50%);
            width: 150px; /* Ukuran logo */
            height: 150px;
            background-image: url('bpjs_logo.png'); /* Ganti dengan path file logo Anda */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15; /* Tingkat transparansi, dibuat samar */
            z-index: 5; /* Di antara desain header (1) dan container form (10) */
        }
        /* Penyesuaian agar form lebih ke bawah sedikit untuk memberi ruang logo */
        @media (max-width: 768px) {
            .logo-watermark {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
<div class="header-design"></div>
<div class="logo-watermark"></div>

<div class="container">
    <h3 class="mb-4">Form Pendaftaran Magang</h3>
    <p class="text-center text-muted">BPJS Ketenagakerjaan</p>
    <hr>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Asal Universitas</label>
            <input type="text" name="asal_univ" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <input type="text" name="jurusan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Durasi Magang</label>
            <input type="text" name="durasi" class="form-control" placeholder="Contoh: 3 bulan" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload CV (PDF/DOCX)</label>
            <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Surat Rekomendasi (PDF/DOCX)</label>
            <input type="file" name="rekomendasi" class="form-control" accept=".pdf,.doc,.docx" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3" style="background-color: #e4002b; border-color: #e4002b;">Kirim Pendaftaran</button>
    </form>
</div>
</body>
</html>