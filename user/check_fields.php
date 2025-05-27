<?php
include __DIR__ . '/config/db.php';

echo "<h2>Database Check Results:</h2>";

// Check event table
$event_query = mysqli_query($conn, "SELECT * FROM event");
if (!$event_query) {
    die("Error checking event table: " . mysqli_error($conn));
}
echo "Events in database: " . mysqli_num_rows($event_query) . "<br><br>";

// Check form fields
$fields_query = mysqli_query($conn, "SELECT * FROM event_form_field");
if (!$fields_query) {
    die("Error checking event_form_field table: " . mysqli_error($conn));
}
echo "Form fields in database: " . mysqli_num_rows($fields_query) . "<br><br>";

echo "<h3>Form Fields Details:</h3>";
while ($row = mysqli_fetch_assoc($fields_query)) {
    echo "Event ID: " . $row['event_id'] . "<br>";
    echo "Field Name: " . $row['nama_field'] . "<br>";
    echo "Field Type: " . $row['tipe_field'] . "<br>";
    echo "Required: " . ($row['wajib'] ? 'Yes' : 'No') . "<br><br>";
}