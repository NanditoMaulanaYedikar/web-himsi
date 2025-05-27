<?php
$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

$query = "SELECT * FROM admins WHERE username = 'admin'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    echo "Username: " . $admin['username'] . "<br>";
    echo "Password hash: " . $admin['password'] . "<br>";
    
    // Test password verification
    $test_password = 'admin123';
    if (password_verify($test_password, $admin['password'])) {
        echo "Password verification: SUCCESS<br>";
    } else {
        echo "Password verification: FAILED<br>";
    }
} else {
    echo "Admin tidak ditemukan!";
}
?> 