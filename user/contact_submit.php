<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the start of the script
function log_debug($message) {
    $log_file = __DIR__ . '/contact_submit_debug.log';
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

log_debug("Script started");

// Check if config file exists
if (!file_exists(__DIR__ . '/config/db.php')) {
    log_debug("Database config file not found!");
    die("Database configuration file not found!");
}

include __DIR__ . '/config/db.php';

// Check database connection
if (!$conn) {
    log_debug("Database connection failed: " . mysqli_connect_error());
    die("Database connection failed: " . mysqli_connect_error());
}

log_debug("Database connected successfully");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log received POST data
    log_debug("Received POST data: " . json_encode($_POST));

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (count($errors) === 0) {
        // Check if table exists
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'contact_messages'");
        if (mysqli_num_rows($table_check) == 0) {
            log_debug("Table 'contact_messages' does not exist!");
            $errors[] = "Database table not found. Please contact administrator.";
        } else {
            log_debug("Table 'contact_messages' exists");
            
            // Prepare and execute insert query
            $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
                if (mysqli_stmt_execute($stmt)) {
                    log_debug("Insert successful");
                    // Redirect back with success message
                    header("Location: /himsi/index.php#contact?success=1");
                    exit;
                } else {
                    $error_msg = "Failed to save message. Error: " . mysqli_stmt_error($stmt);
                    log_debug($error_msg);
                    $errors[] = $error_msg;
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_msg = "Failed to prepare statement. Error: " . mysqli_error($conn);
                log_debug($error_msg);
                $errors[] = $error_msg;
            }
        }
    } else {
        log_debug("Validation errors: " . implode('; ', $errors));
    }

    // If errors, redirect back with error messages
    $error_query = http_build_query(['error' => implode(' ', $errors)]);
    header("Location: /himsi/index.php#contact?$error_query");
    exit;
} else {
    // Invalid request method
    log_debug("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    header("Location: /himsi/index.php#contact");
    exit;
}
