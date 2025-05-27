<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Pastikan parameter ID ada dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$id = (int) $_GET['id'];

// Ambil data thumbnail untuk menghapus file fisik
$query = "SELECT thumbnail FROM galeri_folder WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $thumbnail = $row['thumbnail'];

    // Hapus data dari database
    $delete_sql = "DELETE FROM galeri_folder WHERE id = $id";
    if (mysqli_query($conn, $delete_sql)) {
        // Hapus file thumbnail dari folder
        if ($thumbnail) {
            $thumbnail_path = $root_path . '/img/gallery_img/' . $thumbnail;
            if (file_exists($thumbnail_path)) {
                unlink($thumbnail_path);
            }
        }
        header("Location: index.php?success=deleted");
        exit();
    } else {
        echo "Gagal menghapus data dari database.";
    }
} else {
    echo "Data tidak ditemukan.";
}
?>
