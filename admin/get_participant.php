<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID tidak diberikan']);
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT data_peserta FROM peserta WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    header('Content-Type: application/json');
    echo $row['data_peserta']; // asumsikan sudah JSON
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Peserta tidak ditemukan']);
}
?>
