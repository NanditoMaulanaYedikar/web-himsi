<?php
include '../config/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=peserta.csv');

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
$query = "SELECT p.*, e.nama as event_nama FROM peserta p JOIN event e ON p.event_id = e.id";
if ($event_id) {
    $query .= " WHERE p.event_id = $event_id";
}
$result = mysqli_query($conn, $query);

$output = fopen('php://output', 'w');
fputcsv($output, ['Event', 'Data Peserta', 'Tanggal Daftar']);

while ($row = mysqli_fetch_assoc($result)) {
    $data = json_decode($row['data_peserta'], true);
    $pesertaData = '';
    foreach ($data as $key => $value) {
        $pesertaData .= "$key: $value; ";
    }
    fputcsv($output, [$row['event_nama'], $pesertaData, $row['created_at']]);
}
fclose($output);
?>
