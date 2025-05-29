<<<<<<< HEAD
<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Cek apakah kode departemen dikirimkan dan valid
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit();
}

$kode = $_GET['kode'];

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

// Pastikan departemen ada
$query = "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Cek apakah ada anggota yang masih terhubung dengan departemen ini
    $check_relasi = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM anggota WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");
    $relasi = mysqli_fetch_assoc($check_relasi);

    if ($relasi['jumlah'] > 0) {
        echo "Tidak dapat menghapus karena masih ada anggota yang terhubung dengan departemen ini.";
        exit();
    }

    // Hapus dari database
    $delete_sql = "DELETE FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: index.php?success=deleted");
        exit();
    } else {
        echo "Gagal menghapus departemen dari database.";
    }
} else {
    echo "Departemen tidak ditemukan.";
}
?>
=======
<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Cek apakah kode departemen dikirimkan dan valid
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit();
}

$kode = $_GET['kode'];

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

// Pastikan departemen ada
$query = "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Cek apakah ada anggota yang masih terhubung dengan departemen ini
    $check_relasi = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM anggota WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");
    $relasi = mysqli_fetch_assoc($check_relasi);

    if ($relasi['jumlah'] > 0) {
        echo "Tidak dapat menghapus karena masih ada anggota yang terhubung dengan departemen ini.";
        exit();
    }

    // Hapus dari database
    $delete_sql = "DELETE FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: index.php?success=deleted");
        exit();
    } else {
        echo "Gagal menghapus departemen dari database.";
    }
} else {
    echo "Departemen tidak ditemukan.";
}
?>
>>>>>>> e6964740e0c6cf9ccfd74809dbf2030d578a4d3b
