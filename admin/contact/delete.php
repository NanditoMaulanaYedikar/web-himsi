<?php
// Prevent any output before JSON response
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

$root_path = dirname(__DIR__, 2);
include $root_path . '/config/db.php';

// Clear any output that might have been generated
ob_clean();

// Set JSON header
header('Content-Type: application/json');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate message ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Message ID is required']);
    exit;
}

$id = intval($_POST['id']);

try {
    // Delete the message
    $query = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to delete message: ' . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_affected_rows($stmt) === 0) {
        throw new Exception('Message not found');
    }

    mysqli_stmt_close($stmt);
    echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// End output buffering and send
ob_end_flush(); 