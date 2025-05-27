<?php
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['gallery_id'])) {
    header('Location: index.php');
    exit;
}

$gallery_id = intval($_GET['gallery_id']);

// Get current status
$sql = "SELECT status FROM galeri_folder WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $gallery_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $current_status);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}
mysqli_stmt_close($stmt);

// Toggle status
$new_status = ($current_status === 'active') ? 'inactive' : 'active';

$sql_update = "UPDATE galeri_folder SET status = ? WHERE id = ?";
$stmt_update = mysqli_prepare($conn, $sql_update);
mysqli_stmt_bind_param($stmt_update, 'si', $new_status, $gallery_id);
mysqli_stmt_execute($stmt_update);
mysqli_stmt_close($stmt_update);

header('Location: index.php');
exit;
?>
