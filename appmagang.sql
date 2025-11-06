-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Nov 2025 pada 11.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appmagang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hr_users`
--

CREATE TABLE `hr_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hr_users`
--

INSERT INTO `hr_users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin@example.com', '2025-10-30 03:43:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peserta_magang`
--

CREATE TABLE `peserta_magang` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `universitas` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `durasi` varchar(100) DEFAULT NULL,
  `status` enum('Menunggu','Diterima','Ditolak') DEFAULT 'Menunggu',
  `tanggal_daftar` datetime DEFAULT current_timestamp(),
  `cv` varchar(255) DEFAULT NULL,
  `rekomendasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peserta_magang`
--

INSERT INTO `peserta_magang` (`id`, `nama`, `email`, `universitas`, `jurusan`, `no_hp`, `durasi`, `status`, `tanggal_daftar`, `cv`, `rekomendasi`) VALUES
(1, 'Daffa Fachrezy', 'daffa@mail.com', 'Universitas Ahmad Dahlan', 'Informatika', '08123456789', '3 Bulan', 'Menunggu', '2025-10-30 10:55:45', NULL, NULL),
(2, 'Sinta Aulia', 'sinta@mail.com', 'Universitas Gadjah Mada', 'Teknik Industri', '08234567890', '2 Bulan', 'Diterima', '2025-10-30 10:55:45', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hr_users`
--
ALTER TABLE `hr_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `peserta_magang`
--
ALTER TABLE `peserta_magang`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hr_users`
--
ALTER TABLE `hr_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `peserta_magang`
--
ALTER TABLE `peserta_magang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
