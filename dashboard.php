<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_config.php';

// Cek login
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

// Ambil data peserta magang
$stmt = $pdo->prepare("SELECT * FROM peserta_magang ORDER BY tanggal_daftar DESC");
$stmt->execute();
$peserta = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Peserta Magang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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
  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
    <div class="alert alert-success text-center">
      ✅ Status peserta berhasil diperbarui!
    </div>
  <?php endif; ?>

  <h3 class="fw-bold text-primary mb-3">Daftar Peserta Magang</h3>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
            </tr>
          </thead>
          <tbody>
            <?php if ($peserta): ?>
              <?php foreach ($peserta as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nama']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['universitas'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($row['jurusan'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($row['durasi'] ?? '-') ?></td>

                  <td class="text-center">
                    <?php if (!empty($row['cv'])): ?>
                      <a href="uploads/<?= htmlspecialchars($row['cv']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <?php if (!empty($row['rekomendasi'])): ?>
                      <a href="uploads/<?= htmlspecialchars($row['rekomendasi']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
  <?php if ($row['status'] == 'Menunggu'): ?>
    <form action="update_status.php" method="POST" style="display:inline;">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <input type="hidden" name="status" value="Diterima">
      <button type="submit" class="btn btn-success btn-sm">Terima</button>
    </form>
    <form action="update_status.php" method="POST" style="display:inline;">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <input type="hidden" name="status" value="Ditolak">
      <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
    </form>
  <?php else: ?>
    <span class="badge <?= $row['status']=='Diterima'?'bg-success':'bg-danger' ?>">
      <?= htmlspecialchars($row['status']) ?>
    </span>
  <?php endif; ?>
</td>


                  <td><?= date('d M Y, H:i', strtotime($row['tanggal_daftar'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="10" class="text-center text-muted py-3">Belum ada peserta yang mendaftar.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
