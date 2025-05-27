<?php
session_start();

// Check if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include koneksi database
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

// Validasi folder_id dan foto_id (id foto) dari URL
if (!isset($_GET['folder_id']) || !is_numeric($_GET['folder_id'])) {
    echo "<script>alert('Folder ID tidak valid.'); window.location='index.php';</script>";
    exit;
}
$folder_id = (int) $_GET['folder_id'];

if (!isset($_GET['delete_id']) || !is_numeric($_GET['delete_id'])) {
    echo "<script>alert('Foto ID tidak valid.'); window.location='index.php?folder_id={$folder_id}';</script>";
    exit;
}
$delete_id = (int) $_GET['delete_id'];

// Ambil nama file foto berdasarkan id dan folder_id untuk keamanan
$sql = "SELECT nama_file FROM galeri_foto WHERE id = ? AND galeri_folder_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $delete_id, $folder_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama_file);
if (mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);

    // Hapus file fisik di folder
    $file_path = $root_path . '/img/gallery_img/' . $nama_file;
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Hapus record dari database
    $del_sql = "DELETE FROM galeri_foto WHERE id = ?";
    $del_stmt = mysqli_prepare($conn, $del_sql);
    mysqli_stmt_bind_param($del_stmt, "i", $delete_id);
    if (mysqli_stmt_execute($del_stmt)) {
        mysqli_stmt_close($del_stmt);
        header("Location: index.php?folder_id={$folder_id}&msg=deleted");
        exit();
    } else {
        mysqli_stmt_close($del_stmt);
        echo "<script>alert('Gagal menghapus data dari database.'); window.location='index.php?folder_id={$folder_id}';</script>";
        exit();
    }
} else {
    mysqli_stmt_close($stmt);
    echo "<script>alert('Foto tidak ditemukan.'); window.location='index.php?folder_id={$folder_id}';</script>";
    exit();
}
