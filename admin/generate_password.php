<?php
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Password Information:</h3>";
echo "Password asli: " . $password . "<br>";
echo "Password hash: " . $hashed_password . "<br>";

// Test verification
if (password_verify($password, $hashed_password)) {
    echo "<br>Verifikasi password berhasil!<br>";
} else {
    echo "<br>Verifikasi password gagal!<br>";
}

// SQL untuk update password
echo "<br><h3>SQL untuk update password:</h3>";
echo "UPDATE admins SET password = '" . $hashed_password . "' WHERE username = 'admin';";

// SQL untuk insert admin baru (jika belum ada)
echo "<br><br><h3>SQL untuk insert admin baru:</h3>";
echo "INSERT INTO admins (username, password, name, email) VALUES ('admin', '" . $hashed_password . "', 'Administrator', 'admin@himsi.com');";
?> 