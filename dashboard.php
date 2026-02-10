<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db_config.php';

// ===== CEK LOGIN =====
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

// ===== SEARCH =====
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// ===== STATISTIK =====
$totalPeserta = $pdo->query("SELECT COUNT(*) FROM peserta_magang")->fetchColumn();

$statusData = $pdo->query("
    SELECT status, COUNT(*) AS jumlah 
    FROM peserta_magang 
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Statistik Universitas
$univStats = $pdo->query("
    SELECT universitas, COUNT(*) AS jumlah 
    FROM peserta_magang 
    GROUP BY universitas 
    ORDER BY jumlah DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===== DATA PESERTA =====
if ($search) {
    $stmt = $pdo->prepare("
        SELECT * FROM peserta_magang
        WHERE nama LIKE :search
        OR email LIKE :search
        OR universitas LIKE :search
        OR jurusan LIKE :search
        ORDER BY tanggal_daftar DESC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM peserta_magang ORDER BY tanggal_daftar DESC");
    $stmt->execute();
}

$peserta = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f8f9fa;
    font-family:Poppins, sans-serif;
}
.table th, .table td {
    white-space: nowrap;
    font-size:14px;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary shadow">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a href="index.html" class="navbar-brand mb-0 h1 fw-semibold text-white text-decoration-none">Dashboard Admin</a>
    <div>
      <span class="text-white me-3">👤 <?= htmlspecialchars($_SESSION['user']) ?></span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">

<!-- ===== STATISTIK ===== -->
<div class="row mb-4">
  <div class="col-md-3 col-6 mb-2">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <small>Total Peserta</small>
        <h4 class="fw-bold"><?= $totalPeserta ?></h4>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6 mb-2">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <small>Diterima</small>
        <h4 class="fw-bold text-success"><?= $statusData['Diterima'] ?? 0 ?></h4>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6 mb-2">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <small>Ditolak</small>
        <h4 class="fw-bold text-danger"><?= $statusData['Ditolak'] ?? 0 ?></h4>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6 mb-2">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <small>Menunggu</small>
        <h4 class="fw-bold text-warning"><?= $statusData['Menunggu'] ?? 0 ?></h4>
      </div>
    </div>
  </div>
</div>

<!-- ===== STATISTIK UNIVERSITAS ===== -->
<div class="card shadow-sm mb-4">
  <div class="card-header fw-semibold">
    📊 Statistik Peserta per Universitas
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0">
      <thead class="table-light">
        <tr>
          <th>Universitas</th>
          <th>Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($univStats as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['universitas'] ?? '-') ?></td>
          <td><?= $u['jumlah'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ===== HEADER + SEARCH ===== -->
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
  <h3 class="fw-bold text-primary mb-2">Daftar Peserta Magang</h3>

  <form method="get" class="d-flex gap-2 mb-2">
    <input type="text" name="search" class="form-control"
      placeholder="🔍 Cari peserta"
      value="<?= htmlspecialchars($search) ?>"
      style="max-width:300px;">
    <button class="btn btn-primary">Cari</button>
  </form>

  <a href="export_excel.php" class="btn btn-success btn-sm">📤 Export Excel</a>
</div>

<!-- ===== TABEL PESERTA ===== -->
<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle mb-0">
        <thead class="table-primary text-center">
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Universitas</th>
            <th>Jurusan</th>
            <th>No HP</th>
            <th>Durasi</th>
            <th>CV</th>
            <th>Rekomendasi</th>
            <th>Status</th>
            <th>Tanggal Daftar</th>
            <th>Aksi</th> <!-- ⬅️ TAMBAH -->
          </tr>
        </thead>
        <tbody>
<?php if ($peserta): foreach ($peserta as $row): ?>
  <tr>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['universitas'] ?? '-') ?></td>
    <td><?= htmlspecialchars($row['jurusan'] ?? '-') ?></td>
    <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
    <td><?= htmlspecialchars($row['durasi'] ?? '-') ?></td>

    <td class="text-center">
      <?= $row['cv']
        ? "<a href='uploads/".htmlspecialchars($row['cv'])."' target='_blank' class='btn btn-outline-primary btn-sm'>Lihat</a>"
        : "-" ?>
    </td>

    <td class="text-center">
      <?= $row['rekomendasi']
        ? "<a href='uploads/".htmlspecialchars($row['rekomendasi'])."' target='_blank' class='btn btn-outline-primary btn-sm'>Lihat</a>"
        : "-" ?>
    </td>

    <!-- STATUS -->
    <td class="text-center">
      <?php if ($row['status'] === 'Menunggu'): ?>
        <form method="POST" action="update_status.php" class="d-inline">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="hidden" name="status" value="Diterima">
          <button class="btn btn-success btn-sm">Terima</button>
        </form>
        <form method="POST" action="update_status.php" class="d-inline">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="hidden" name="status" value="Ditolak">
          <button class="btn btn-danger btn-sm">Tolak</button>
        </form>
      <?php else: ?>
        <span class="badge <?= $row['status']=='Diterima'?'bg-success':'bg-danger' ?>">
          <?= htmlspecialchars($row['status']) ?>
        </span>
      <?php endif; ?>
    </td>

    <td><?= date('d M Y H:i', strtotime($row['tanggal_daftar'])) ?></td>

    <!-- AKSI HAPUS -->
    <td class="text-center">
      <form method="POST" action="hapus_peserta.php"
            onsubmit="return confirm('⚠️ Yakin ingin menghapus data ini?');">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" class="btn btn-outline-danger btn-sm">
          🗑 Hapus
        </button>
      </form>
    </td>

  </tr>
<?php endforeach; else: ?>
  <tr>
    <td colspan="11" class="text-center text-muted py-3">
      Belum ada peserta
    </td>
  </tr>
<?php endif; ?>
</tbody>

      </table>
    </div>
  </div>
</div>

</div>
</body>
</html>
