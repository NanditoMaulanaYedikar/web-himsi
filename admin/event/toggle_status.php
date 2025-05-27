<?php
// Fix relative path to config
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    echo "<script>alert('ID event tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$event_id = intval($_GET['event_id']);

// Get current status
$query = mysqli_query($conn, "SELECT status FROM event WHERE id = '$event_id'");
if ($query && $row = mysqli_fetch_assoc($query)) {
    // Toggle status
    $new_status = ($row['status'] == 'open') ? 'closed' : 'open';
    
    $update = mysqli_query($conn, "UPDATE event SET status = '$new_status' WHERE id = '$event_id'");
    
    if ($update) {
        echo "<script>
            alert('Status event berhasil diubah menjadi " . strtoupper($new_status) . "!');
            window.location='index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengubah status: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>alert('Event tidak ditemukan.'); window.history.back();</script>";
}
?>
