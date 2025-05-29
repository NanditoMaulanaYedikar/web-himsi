<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . '/../../../config/db.php';

$kode_departemen = $_GET['kode_departemen'] ?? '';
$nim = $_GET['nim'] ?? '';

if (!$kode_departemen || !$nim) {
    // Kalau parameter kurang, redirect ke halaman anggota
    header("Location: lihat_anggota.php?kode_departemen=" . urlencode($kode_departemen));
    exit();
}

// Ambil data anggota dulu untuk dapatkan nama file foto
$res = mysqli_query($conn, "SELECT foto FROM anggota WHERE nim = '" . mysqli_real_escape_string($conn, $nim) . "' AND kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
$anggota = mysqli_fetch_assoc($res);

if ($anggota) {
    // Hapus file foto jika ada
    $foto_filename = $anggota['foto'];
    $foto_path = __DIR__ . '/../../../img/anggota/' . $foto_filename;
    if ($foto_filename && file_exists($foto_path)) {
        unlink($foto_path);
    }

    // Hapus data anggota dari database
    $stmt = mysqli_prepare($conn, "DELETE FROM anggota WHERE nim = ? AND kode_departemen = ?");
    mysqli_stmt_bind_param($stmt, "ss", $nim, $kode_departemen);
    mysqli_stmt_execute($stmt);
}

// Redirect kembali ke halaman daftar anggota
header("Location: lihat_anggota.php?kode_departemen=" . urlencode($kode_departemen));
exit();
