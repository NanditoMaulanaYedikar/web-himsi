<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . '/config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate event_id
if (!isset($_POST['event_id']) || empty($_POST['event_id'])) {
    echo json_encode(['success' => false, 'message' => 'Event ID tidak valid']);
    exit;
}

$event_id = intval($_POST['event_id']);

// Verify event exists and is open
$event_check = mysqli_query($conn, "SELECT id, status FROM event WHERE id = '$event_id'");
if (!$event_check || mysqli_num_rows($event_check) === 0) {
    echo json_encode(['success' => false, 'message' => 'Event tidak ditemukan']);
    exit;
}

$event = mysqli_fetch_assoc($event_check);
if ($event['status'] !== 'open') {
    echo json_encode(['success' => false, 'message' => 'Pendaftaran untuk event ini sudah ditutup']);
    exit;
}

// Get form fields for this event
$fields_query = mysqli_query($conn, "SELECT * FROM event_form_field WHERE event_id = '$event_id'");
if (!$fields_query) {
    echo json_encode(['success' => false, 'message' => 'Error mengambil data form: ' . mysqli_error($conn)]);
    exit;
}

$data_peserta = array();

// Process each field
while ($field = mysqli_fetch_assoc($fields_query)) {
    $field_id = $field['id'];
    
    // Handle file uploads
    if ($field['tipe_field'] === 'file') {
        if (isset($_FILES['field']) && isset($_FILES['field']['name'][$field_id])) {
            $file = $_FILES['field']['name'][$field_id];
            $temp = $_FILES['field']['tmp_name'][$field_id];
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            
            // Generate unique filename
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = __DIR__ . '/asset/uploads/' . $new_filename;
            
            // Create uploads directory if it doesn't exist
            if (!file_exists(__DIR__ . '/asset/uploads')) {
                mkdir(__DIR__ . '/asset/uploads', 0777, true);
            }
            
            if (move_uploaded_file($temp, $upload_path)) {
                $data_peserta[$field['nama_field']] = $new_filename;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error mengupload file']);
                exit;
            }
        } elseif ($field['wajib']) {
            echo json_encode(['success' => false, 'message' => "File {$field['nama_field']} wajib diupload"]);
            exit;
        }
    } 
    // Handle other field types
    else {
        if (isset($_POST['field'][$field_id])) {
            $value = $_POST['field'][$field_id];
            
            // Validate email
            if ($field['tipe_field'] === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
                exit;
            }
            
            // Validate number
            if ($field['tipe_field'] === 'number' && !is_numeric($value)) {
                echo json_encode(['success' => false, 'message' => 'Format nomor tidak valid']);
                exit;
            }
            
            $data_peserta[$field['nama_field']] = mysqli_real_escape_string($conn, $value);
        } elseif ($field['wajib']) {
            echo json_encode(['success' => false, 'message' => "Field {$field['nama_field']} wajib diisi"]);
            exit;
        }
    }
}

// Convert data to JSON
$json_data = json_encode($data_peserta);

// Insert into database
$insert = mysqli_query($conn, "INSERT INTO peserta (event_id, data_peserta) VALUES ('$event_id', '$json_data')");

ob_clean();
if ($insert) {
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}
?>
