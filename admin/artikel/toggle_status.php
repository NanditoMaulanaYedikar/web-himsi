<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['artikel_id'])) {
    header('Location: index.php');
    exit;
}

$artikel_id = intval($_GET['artikel_id']);

// Ambil status saat ini
$sql = "SELECT status FROM artikel WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Gagal menyiapkan query: " . $conn->error);
}
$stmt->bind_param('i', $artikel_id);
$stmt->execute();
$stmt->bind_result($current_status);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: index.php');
    exit;
}
$stmt->close();

// Toggle status
$new_status = ($current_status === 'publish') ? 'nopublish' : 'publish';

// Update status
$sql_update = "UPDATE artikel SET status = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    die("Gagal menyiapkan query update: " . $conn->error);
}
$stmt_update->bind_param('si', $new_status, $artikel_id);
$stmt_update->execute();
$stmt_update->close();

header('Location: index.php');
exit;
