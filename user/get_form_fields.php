<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include __DIR__ . '/config/db.php';
header('Content-Type: application/json');

if (!isset($_GET['event_id'])) {
    echo json_encode(['error' => 'Event ID is required']);
    exit;
}

$event_id = intval($_GET['event_id']);

// Get form fields for the event
$query = mysqli_query($conn, "SELECT * FROM event_form_field WHERE event_id = '$event_id'");

if (!$query) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

$fields = [];
while ($row = mysqli_fetch_assoc($query)) {
    $fields[] = $row;
}

ob_clean();
echo json_encode($fields);
?>
