<?php
// Fix relative path to config
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID field tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$field_id = intval($_GET['id']);

// Get event_id before deleting the field
$query = mysqli_query($conn, "SELECT event_id FROM event_form_field WHERE id = '$field_id'");
if ($query && $row = mysqli_fetch_assoc($query)) {
    $event_id = $row['event_id'];
    
    // Delete the field
    $delete = mysqli_query($conn, "DELETE FROM event_form_field WHERE id = '$field_id'");
    
    if ($delete) {
        echo "<script>
            alert('Field berhasil dihapus!');
            window.location='field.php?event_id=" . $event_id . "';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus field: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>alert('Field tidak ditemukan.'); window.history.back();</script>";
}
?>
