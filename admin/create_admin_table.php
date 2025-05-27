<?php
$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

// Create admins table
$query = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $query)) {
    echo "Table admins created successfully<br>";
    
    // Insert default admin if not exists
    $check_admin = "SELECT * FROM admins WHERE username = 'admin'";
    $result = mysqli_query($conn, $check_admin);
    
    if (mysqli_num_rows($result) == 0) {
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $insert_admin = "INSERT INTO admins (username, password, name, email) 
                        VALUES ('admin', '$default_password', 'Administrator', 'admin@himsi.com')";
        mysqli_query($conn, $insert_admin);
        echo "Default admin created successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Admin table already exists with default admin!";
    }
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?> 