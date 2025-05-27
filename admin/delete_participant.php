<?php
include '../config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID tidak diberikan']);
    exit;
}

$query = "DELETE FROM peserta WHERE id = $id";
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus peserta']);
}
?>
