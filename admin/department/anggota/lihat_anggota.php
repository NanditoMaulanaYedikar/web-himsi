<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database config dengan path yang benar
include_once __DIR__ . '/../../../config/db.php';

$kode_departemen = $_GET['kode_departemen'] ?? '';

if (!$kode_departemen) {
    header("Location: ../index.php");
    exit();
}

// Ambil nama departemen
$departemen_result = mysqli_query($conn, "SELECT nama_departemen FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
$departemen = mysqli_fetch_assoc($departemen_result);
$nama_departemen = $departemen['nama_departemen'] ?? 'Tidak diketahui';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Anggota Departemen - <?= htmlspecialchars($nama_departemen) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include_once '../../sidebar.php'; ?>
    <header class="bg-white shadow ml-64">
        <div class="max-w-7xl mx-auto p-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">Anggota Departemen: <?= htmlspecialchars($nama_departemen) ?></h1>
            <div class="flex items-center space-x-3">
                <a href="../index.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
                <a href="tambah_anggota.php?kode_departemen=<?= urlencode($kode_departemen) ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Anggota
                </a>
            </div>
        </div>
    </header>
    <main class="ml-64 p-4 max-w-7xl mx-auto bg-white rounded shadow">
        <table class="w-full table-auto divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">NIM</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Foto</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th> <!-- Kolom baru -->
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM anggota WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td class="px-4 py-2 text-sm text-center"><?= $no++ ?></td>
                    <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['nim']) ?></td>
                    <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2 text-sm">
                        <?php if ($row['foto']): ?>
                            <img src="../../../img/anggota/<?= htmlspecialchars($row['foto']) ?>" alt="Foto" class="w-12 h-12 rounded-full object-cover border">
                        <?php else: ?>
                            <em class="text-gray-400">Tidak ada foto</em>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2 text-sm space-x-2">
                        <a href="edit_anggota.php?nim=<?= urlencode($row['nim']) ?>&kode_departemen=<?= urlencode($kode_departemen) ?>" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">Edit</a>
                        <a href="hapus_anggota.php?nim=<?= urlencode($row['nim']) ?>&kode_departemen=<?= urlencode($kode_departemen) ?>" 
                           onclick="return confirm('Yakin ingin menghapus anggota <?= htmlspecialchars($row['nama']) ?>?');" 
                           class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">Delete</a>
                    </td>
                </tr>
                <?php } ?>
                <?php if (mysqli_num_rows($query) === 0): ?>
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">Belum ada anggota di departemen ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
