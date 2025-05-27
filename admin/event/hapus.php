<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include __DIR__ . '/../../config/db.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID event tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

$id = $_GET['id'];

// Fetch event data to get image filename
$result = mysqli_query($conn, "SELECT * FROM event WHERE id = $id");
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Event tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}
$event = mysqli_fetch_assoc($result);

// Delete event from database
$delete = mysqli_query($conn, "DELETE FROM event WHERE id = $id");

if ($delete) {
    // Delete image file
    if (file_exists("../../asset/event_img/" . $event['gambar'])) {
        unlink("../../asset/event_img/" . $event['gambar']);
    }
    echo "<script>alert('Event berhasil dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus event.'); window.location='index.php';</script>";
}
?>
