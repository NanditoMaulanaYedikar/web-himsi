<?php
// Fix relative path to config
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Event ID tidak ditemukan");
}

$event_id = intval($_GET['event_id']);

// Get event details
$event_query = mysqli_query($conn, "SELECT nama FROM event WHERE id = '$event_id'");
if (!$event_query || mysqli_num_rows($event_query) === 0) {
    die("Event tidak ditemukan");
}
$event = mysqli_fetch_assoc($event_query);

// Get event form fields with types
$fields_query = mysqli_query($conn, "SELECT nama_field FROM event_form_field WHERE event_id = '$event_id' ORDER BY id ASC");
$fields = [];
while ($field = mysqli_fetch_assoc($fields_query)) {
    $fields[] = $field['nama_field'];
}

// Get registrations
$registrations_query = mysqli_query($conn, "SELECT * FROM peserta WHERE event_id = '$event_id' ORDER BY created_at DESC");

// Set headers for CSV download
$filename = "registrasi_" . str_replace(" ", "_", strtolower($event['nama'])) . "_" . date("Y-m-d") . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for proper Excel display
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Output the column headings
$headers = array_merge(['No'], $fields, ['Tanggal Daftar']);
fputcsv($output, $headers);

// Output the data
$no = 1;
while ($row = mysqli_fetch_assoc($registrations_query)) {
    $data = json_decode($row['data_peserta'], true);
    
    $row_data = [$no++];
    foreach ($fields as $field_name) {
        $value = $data[$field_name] ?? '-';
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        $row_data[] = $value;
    }
    $row_data[] = date('d/m/Y H:i', strtotime($row['created_at']));
    
    fputcsv($output, $row_data);
}

// Close the file pointer
fclose($output);
exit();
?>
