<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . '/../../../config/db.php';
include_once __DIR__ . '/../../sidebar.php';

if (!isset($conn) || !$conn) {
    die("Koneksi ke database gagal.");
}

$kode_departemen = $_GET['kode_departemen'] ?? '';
if (!$kode_departemen) {
    header("Location: ../index.php");
    exit();
}

$departemen_result = mysqli_query($conn, "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
$departemen = mysqli_fetch_assoc($departemen_result);
$nama_departemen = $departemen['nama_departemen'] ?? 'Tidak diketahui';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Program Kerja - <?= htmlspecialchars($nama_departemen) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen">
<?php include_once '../sidebar.php'; ?>
<main class="ml-64 p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-lg">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="../index.php" class="inline-flex items-center text-gray-700 hover:text-blue-600 text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Program Kerja - <?= htmlspecialchars($nama_departemen) ?></h1>
        <a href="upload_program.php?kode_departemen=<?= urlencode($kode_departemen) ?>"
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm shadow-sm transition">
            + Tambah Program
        </a>
    </div>

    <!-- Daftar Program -->
    <?php
    $query = mysqli_query($conn, "SELECT * FROM program_kerja WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
    if (mysqli_num_rows($query) > 0): ?>
        <ul class="space-y-4">
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <li class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-800"><?= htmlspecialchars($row['nama_program']) ?></p>
                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($row['deskripsi_program']) ?></p>
                        </div>
                        <div class="flex items-start gap-3">
                            <a href="edit_program.php?id_program=<?= $row['id_program'] ?>&kode_departemen=<?= urlencode($kode_departemen) ?>"
                               class="text-blue-600 text-sm hover:underline">Edit</a>
                            <a href="hapus_program.php?id_program=<?= $row['id_program'] ?>&kode_departemen=<?= urlencode($kode_departemen) ?>"
                               onclick="return confirm('Yakin ingin menghapus program ini?')"
                               class="text-red-600 text-sm hover:underline">Hapus</a>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500 italic">Belum ada program kerja untuk departemen ini.</p>
    <?php endif; ?>
</main>
</body>
</html>
