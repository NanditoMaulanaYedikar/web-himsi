<?php
include __DIR__ . '/config/db.php';

// Add test fields for event ID 1
$fields = [
    ['Nama Lengkap', 'text', 1],
    ['Email', 'email', 1],
    ['No. Telepon', 'number', 1]
];

foreach ($fields as $field) {
    $nama_field = mysqli_real_escape_string($conn, $field[0]);
    $tipe_field = mysqli_real_escape_string($conn, $field[1]);
    $wajib = $field[2];
    
    $query = "INSERT INTO event_form_field (event_id, nama_field, tipe_field, wajib) 
              VALUES (1, '$nama_field', '$tipe_field', $wajib)";
    
    if (mysqli_query($conn, $query)) {
        echo "Berhasil menambahkan field: $nama_field<br>";
    } else {
        echo "Error menambahkan field $nama_field: " . mysqli_error($conn) . "<br>";
    }
}

echo "Setup selesai!";
?>
