<?php
include 'db_config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daftar_peserta_magang.xls");

echo "<table border='1'>";
echo "<tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Universitas</th>
        <th>Jurusan</th>
        <th>No HP</th>
        <th>Durasi</th>
        <th>Status</th>
        <th>Tanggal Daftar</th>
      </tr>";

$stmt = $pdo->prepare("SELECT * FROM peserta_magang ORDER BY tanggal_daftar DESC");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td>{$row['email']}</td>
            <td>{$row['universitas']}</td>
            <td>{$row['jurusan']}</td>
            <td>{$row['no_hp']}</td>
            <td>{$row['durasi']}</td>
            <td>{$row['status']}</td>
            <td>{$row['tanggal_daftar']}</td>
          </tr>";
}

echo "</table>";
?>
