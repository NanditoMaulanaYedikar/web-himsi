<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . '/../../../config/db.php';

$id_program = $_GET['id_program'] ?? '';
$kode_departemen = $_GET['kode_departemen'] ?? '';

if ($id_program && $kode_departemen) {
    $query = mysqli_query($conn, "DELETE FROM program_kerja WHERE id_program = " . intval($id_program));
}

header("Location: lihat_program.php?kode_departemen=" . urlencode($kode_departemen));
exit();
