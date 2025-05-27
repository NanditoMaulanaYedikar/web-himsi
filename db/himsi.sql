-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 06:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `himsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', '$2y$10$NIeQOH2AVdLHXeggcT/M1.QcgP6AVWVIaFiShUd4iculJlhrU5fTC', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `nim` varchar(20) NOT NULL,
  `foto` text DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `kode_departemen` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi_artikel` text NOT NULL,
  `isi` text NOT NULL,
  `gambar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `deskripsi_gambar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` enum('publish','nopublish') NOT NULL,
  `jumlah_dilihat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `judul`, `deskripsi_artikel`, `isi`, `gambar`, `deskripsi_gambar`, `tanggal`, `status`, `jumlah_dilihat`) VALUES
(85, 'Venezuela’s Dictator Can’t Even Lie Well', 'Nicolás Maduro stole the election. So why is a longtime opposition leader in such a good mood?', 'López knows about Venezuelan prisons because he spent more than three years in them; three years of house arrest followed. The charges were trumped up. His real crime was first to be elected mayor of Chacao, a part of Caracas; then to become one of Venezuela’s most popular opposition leaders; then to be a leader of mass protests. He finally escaped the country in 2020 and now lives mostly in Spain. But he was in Washington yesterday, two days after Sunday’s dramatic Venezuelan presidential election, and we had a chance to speak.As we were speaking, Leopoldo López’s telephone kept buzzing. The national director of his political movement, Voluntad Popular, had just been arrested in Caracas. López had spoken to Freddy Superlano earlier in the morning. “I know they are coming for me, but I’m not scared,” Superlano had told him. Well, López had responded, “prison is not the end of the world.”\r\n\r\nLópez knows about Venezuelan prisons because he spent more than three years in them; three years of house arrest followed. The charges were trumped up. His real crime was first to be elected mayor of Chacao, a part of Caracas; then to become one of Venezuela’s most popular opposition leaders; then to be a leader of mass protests. He finally escaped the country in 2020 and now lives mostly in Spain. But he was in Washington yesterday, two days after Sunday’s dramatic Venezuelan presidential election, and we had a chance to speak.\r\n\r\nAs we were speaking, Leopoldo López’s telephone kept buzzing. The national director of his political movement, Voluntad Popular, had just been arrested in Caracas. López had spoken to Freddy Superlano earlier in the morning. “I know they are coming for me, but I’m not scared,” Superlano had told him. Well, López had responded, “prison is not the end of the world.”\r\n\r\nLópez knows about Venezuelan prisons because he spent more than three years in them; three years of house arrest followed. The charges were trumped up. His real crime was first to be elected mayor of Chacao, a part of Caracas; then to become one of Venezuela’s most popular opposition leaders; then to be a leader of mass protests. He finally escaped the country in 2020 and now lives mostly in Spain. But he was in Washington yesterday, two days after Sunday’s dramatic Venezuelan presidential election, and we had a chance to speak.As we were speaking, Leopoldo López’s telephone kept buzzing. The national director of his political movement, Voluntad Popular, had just been arrested in Caracas. López had spoken to Freddy Superlano earlier in the morning. “I know they are coming for me, but I’m not scared,” Superlano had told him. Well, López had responded, “prison is not the end of the world.”', '1748161870_6832d54e0e980.jpg', 'Ada berbagai jenis artikel, seperti artikel ilmiah, artikel opini, artikel ulasan, dan lain-lain.', '2025-05-25 15:31:10', 'publish', 2),
(86, 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', '1748162503_6832d7c7cda03.jpg', 'Venezuela’s Dictator Can’t Even Lie Well', '2025-05-25 15:41:43', 'publish', 0),
(87, 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', '1748163585_6832dc0121e20.jpg', 'Venezuela’s Dictator Can’t Even Lie Well', '2025-05-25 15:59:45', 'publish', 0),
(88, 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', 'Venezuela’s Dictator Can’t Even Lie Well', '1748163607_6832dc17c04dd.jpg', 'Venezuela’s Dictator Can’t Even Lie Well', '2025-05-25 16:00:07', 'publish', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(6, 'raihan zaky', 'raihanzakym@gmail.com', 'media partner', 'addada', '2025-05-01 17:22:54'),
(7, 'ayam segar', 'vertex82@box.rekadev.org', 'jgjgj', 'sdsdsd', '2025-05-02 11:45:15'),
(8, 'nandito', 'bangdito87@gmail.com', 'p', 'p', '2025-05-23 10:09:04');

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `kode_departemen` varchar(10) NOT NULL,
  `nama_departemen` varchar(100) NOT NULL,
  `deskripsi_departemen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tempat` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `nama`, `deskripsi`, `tanggal`, `tempat`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(5, 'event Puasa', 'event puasa bersama real madrid', '2025-05-31', 'Santiago bernebau', '68101c3bb4340.png', 'open', '2025-04-29 00:24:27', '2025-05-23 07:21:54'),
(6, 'ngobar', 'ngoding bareng', '2025-05-31', 'Kampus C', '68106526a2771.png', 'open', '2025-04-29 05:35:34', '2025-05-23 07:21:16'),
(10, 'ngobar', 'pp', '2025-05-31', 'kampus c', '683020c25cb13.jpg', 'closed', '2025-05-23 07:10:08', '2025-05-23 07:21:26');

-- --------------------------------------------------------

--
-- Table structure for table `event_form_field`
--

CREATE TABLE `event_form_field` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `nama_field` varchar(255) NOT NULL,
  `tipe_field` varchar(50) NOT NULL,
  `wajib` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_form_field`
--

INSERT INTO `event_form_field` (`id`, `event_id`, `nama_field`, `tipe_field`, `wajib`) VALUES
(14, 5, 'Nama', 'text', 1),
(15, 5, 'Fans', 'text', 1),
(16, 5, 'Jumlah skor barca vs madrid', 'number', 1),
(17, 5, 'foto tangisan kamu', 'file', 1),
(18, 6, 'Nama', 'text', 1),
(19, 6, 'NIM', 'number', 1),
(20, 6, 'Email', 'email', 1);

-- --------------------------------------------------------

--
-- Table structure for table `galeri_folder`
--

CREATE TABLE `galeri_folder` (
  `id` int(11) NOT NULL,
  `nama_folder` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri_folder`
--

INSERT INTO `galeri_folder` (`id`, `nama_folder`, `deskripsi`, `thumbnail`, `status`, `created_at`) VALUES
(8, 'Madrid remontada', 'p', '1747988894_6830319ea4614.png', 'active', '2025-05-23 07:59:03'),
(10, 'galeri3', 'galeri3', '1747987265_68302b41cd489.jpeg', 'active', '2025-05-23 08:01:05'),
(11, 'Madrid remontada', 'p', '1748160043_6832ce2b8fe49.png', 'active', '2025-05-25 08:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `galeri_foto`
--

CREATE TABLE `galeri_foto` (
  `id` int(11) NOT NULL,
  `galeri_folder_id` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri_foto`
--

INSERT INTO `galeri_foto` (`id`, `galeri_folder_id`, `nama_file`, `deskripsi`, `created_at`) VALUES
(8, 8, 'aboutbuku.png', 'op', '2025-05-23 08:29:00'),
(9, 8, '1747989149_6830329dcf338.jpg', 'ookok', '2025-05-23 08:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `data_peserta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data_peserta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id`, `event_id`, `data_peserta`, `created_at`) VALUES
(14, 5, '{\"Nama\":\"Demit\",\"Fans\":\"Madrid\",\"Jumlah skor barca vs madrid\":\"23\",\"foto tangisan kamu\":\"68101ccc2ddc5.png\"}', '2025-04-29 00:26:52'),
(15, 5, '{\"Nama\":\"dimas\",\"Fans\":\"man united\",\"Jumlah skor barca vs madrid\":\"23\",\"foto tangisan kamu\":\"681042a5028e2.png\"}', '2025-04-29 03:08:21'),
(16, 6, '{\"Nama\":\"Lisna\",\"NIM\":\"1201228078\",\"Email\":\"lisna@gmail.com\"}', '2025-04-29 05:36:45'),
(17, 6, '{\"Nama\":\"zizah\",\"NIM\":\"11222112\",\"Email\":\"z@gmail.com\"}', '2025-04-29 05:36:59'),
(18, 6, '{\"Nama\":\"Lisna\",\"NIM\":\"12112\",\"Email\":\"lisna@gmail.com\"}', '2025-04-29 06:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `program_kerja`
--

CREATE TABLE `program_kerja` (
  `id_program` bigint(20) UNSIGNED NOT NULL,
  `kode_departemen` varchar(10) DEFAULT NULL,
  `nama_program` text NOT NULL,
  `deskripsi_program` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `kode_departemen` (`kode_departemen`);

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_departemen`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_form_field`
--
ALTER TABLE `event_form_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `galeri_folder`
--
ALTER TABLE `galeri_folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galeri_folder_id` (`galeri_folder_id`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD PRIMARY KEY (`id_program`),
  ADD KEY `kode_departemen` (`kode_departemen`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_form_field`
--
ALTER TABLE `event_form_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `galeri_folder`
--
ALTER TABLE `galeri_folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `program_kerja`
--
ALTER TABLE `program_kerja`
  MODIFY `id_program` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_ibfk_1` FOREIGN KEY (`kode_departemen`) REFERENCES `departemen` (`kode_departemen`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `event_form_field`
--
ALTER TABLE `event_form_field`
  ADD CONSTRAINT `event_form_field_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  ADD CONSTRAINT `galeri_foto_ibfk_1` FOREIGN KEY (`galeri_folder_id`) REFERENCES `galeri_folder` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta`
--
ALTER TABLE `peserta`
  ADD CONSTRAINT `peserta_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_kerja`
--
ALTER TABLE `program_kerja`
  ADD CONSTRAINT `program_kerja_ibfk_1` FOREIGN KEY (`kode_departemen`) REFERENCES `departemen` (`kode_departemen`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
