<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . '/../../../config/db.php';
include_once __DIR__ . '/../../sidebar.php';

$kode_departemen = $_GET['kode_departemen'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_program = $_POST["nama_program"];
    $deskripsi_program = $_POST["deskripsi_program"];

    $query = "INSERT INTO program_kerja (kode_departemen, nama_program, deskripsi_program)
              VALUES (
                  '" . mysqli_real_escape_string($conn, $kode_departemen) . "',
                  '" . mysqli_real_escape_string($conn, $nama_program) . "',
                  '" . mysqli_real_escape_string($conn, $deskripsi_program) . "'
              )";

    if (mysqli_query($conn, $query)) {
        header("Location: lihat_program.php?kode_departemen=" . urlencode($kode_departemen));
        exit();
    } else {
        $error = "Gagal menyimpan program kerja.";
    }
}

// Ambil nama departemen untuk judul
$departemen_result = mysqli_query($conn, "SELECT nama_departemen FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
$departemen = mysqli_fetch_assoc($departemen_result);
$nama_departemen = $departemen['nama_departemen'] ?? 'Tidak diketahui';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Program Kerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen">

<main class="ml-64 p-6 max-w-2xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Program Kerja</h1>
        <p class="text-sm text-gray-500 mb-6">Departemen: <strong><?= htmlspecialchars($nama_departemen) ?></strong></p>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program</label>
                <input type="text" name="nama_program" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-indigo-300 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Program</label>
                <textarea name="deskripsi_program" rows="4" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring focus:ring-indigo-300 focus:outline-none" required></textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="inline-flex items-center bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="lihat_program.php?kode_departemen=<?= urlencode($kode_departemen) ?>"
                   class="text-sm text-gray-600 hover:underline">← Kembali ke daftar program</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
