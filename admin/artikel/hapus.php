<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID artikel tidak valid.');
}

$id = intval($_GET['id']);

// Ambil data artikel terlebih dahulu
$stmt = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$artikel = $result->fetch_assoc();
$stmt->close();

if (!$artikel) {
    die('Artikel tidak ditemukan.');
}

// Hapus file gambar jika ada
$gambar = $artikel['gambar'];
$upload_dir = $root_path . '/img/artikel/';

if (!empty($gambar)) {
    $gambar_path = $upload_dir . $gambar;
    if (file_exists($gambar_path)) {
        unlink($gambar_path);
    }
}

// Hapus artikel dari database
$stmt = $conn->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php");
    exit();
} else {
    $stmt->close();
    die("Gagal menghapus artikel: " . $conn->error);
}
