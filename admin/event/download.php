<?php
// Secure file download script

// Base directory for uploads
$upload_dir = __DIR__ . '../../img/uploads/';

// Validate 'file' parameter
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    echo "Invalid file parameter.";
    exit;
}

$filename = basename($_GET['file']); // prevent directory traversal
$filepath = $upload_dir . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

// Get mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime_type);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

// Clear output buffer
ob_clean();
flush();

// Read the file
readfile($filepath);
exit;
?>
