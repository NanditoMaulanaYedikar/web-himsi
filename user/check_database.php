<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/config/db.php';

echo "<h2>Database Connection Check</h2>";

// Check database connection
if ($conn) {
    echo "✅ Database connection successful<br>";
} else {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Check if tables exist
$tables = ['event', 'event_form_field', 'peserta'];
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Table '$table' exists<br>";
        
        // Show table structure
        $structure = mysqli_query($conn, "DESCRIBE $table");
        echo "<pre>Structure of $table:<br>";
        while ($row = mysqli_fetch_assoc($structure)) {
            echo "  {$row['Field']} - {$row['Type']}<br>";
        }
        echo "</pre>";
        
        // Show record count
        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM $table");
        $count_result = mysqli_fetch_assoc($count);
        echo "Records in $table: {$count_result['total']}<br><br>";
    } else {
        echo "❌ Table '$table' does not exist<br>";
    }
}

// Check event_form_field data
echo "<h3>Form Fields for Event ID 1:</h3>";
$fields = mysqli_query($conn, "SELECT * FROM event_form_field WHERE event_id = 1");
if ($fields && mysqli_num_rows($fields) > 0) {
    while ($row = mysqli_fetch_assoc($fields)) {
        echo "Field ID: {$row['id']}<br>";
        echo "Name: {$row['nama_field']}<br>";
        echo "Type: {$row['tipe_field']}<br>";
        echo "Required: " . ($row['wajib'] ? 'Yes' : 'No') . "<br><br>";
    }
} else {
    echo "No form fields found for Event ID 1<br>";
    echo "SQL Error (if any): " . mysqli_error($conn) . "<br>";
}

// Show available events
echo "<h3>Available Events:</h3>";
$events = mysqli_query($conn, "SELECT * FROM event");
if ($events && mysqli_num_rows($events) > 0) {
    while ($row = mysqli_fetch_assoc($events)) {
        echo "Event ID: {$row['id']}<br>";
        echo "Name: {$row['nama']}<br>";
        echo "Date: {$row['tanggal']}<br><br>";
    }
} else {
    echo "No events found<br>";
}
?>

<br>
<p>Next steps:</p>
<ul>
    <li><a href="insert_fields.php">Insert test fields</a></li>
    <li><a href="pendaftaraan.php?event_id=1">Try registration form</a></li>
    <li><a href="index.php">View events list</a></li>
</ul>
