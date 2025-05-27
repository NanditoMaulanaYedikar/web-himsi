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
    }
} else {
    echo "Error creating admins table: " . mysqli_error($conn) . "<br>";
}

// Create event table
$query = "CREATE TABLE IF NOT EXISTS event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tanggal DATE NOT NULL,
    tempat VARCHAR(255) NOT NULL,
    gambar VARCHAR(255),
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $query)) {
    echo "Table event created successfully<br>";
} else {
    echo "Error creating event table: " . mysqli_error($conn) . "<br>";
}

// Create event_form_field table
$query = "CREATE TABLE IF NOT EXISTS event_form_field (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    nama_field VARCHAR(255) NOT NULL,
    tipe_field ENUM('text', 'number', 'email', 'date', 'checkbox', 'textarea', 'file') NOT NULL,
    wajib BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $query)) {
    echo "Table event_form_field created successfully<br>";
} else {
    echo "Error creating event_form_field table: " . mysqli_error($conn) . "<br>";
}

// Create galeri_folder table
$query = "CREATE TABLE IF NOT EXISTS galeri_folder (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_folder VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    thumbnail VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $query)) {
    echo "Table galeri_folder created successfully<br>";
} else {
    echo "Error creating galeri_folder table: " . mysqli_error($conn) . "<br>";
}

// Create galeri_foto table
$query = "CREATE TABLE IF NOT EXISTS galeri_foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    galeri_folder_id INT NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (galeri_folder_id) REFERENCES galeri_folder(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $query)) {
    echo "Table galeri_foto created successfully<br>";
} else {
    echo "Error creating galeri_foto table: " . mysqli_error($conn) . "<br>";
}

// Create contact_messages table
$query = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $query)) {
    echo "Table contact_messages created successfully<br>";
} else {
    echo "Error creating contact_messages table: " . mysqli_error($conn) . "<br>";
}

echo "<br>All tables have been created successfully!";
?> 