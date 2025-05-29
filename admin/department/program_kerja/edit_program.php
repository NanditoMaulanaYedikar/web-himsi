<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . '/../../../config/db.php';
include_once __DIR__ . '/../../sidebar.php';

$id_program = $_GET['id_program'] ?? '';
$kode_departemen = $_GET['kode_departemen'] ?? '';

if (!$id_program || !$kode_departemen) {
    header("Location: lihat_program.php?kode_departemen=" . urlencode($kode_departemen));
    exit();
}

// Ambil data program
$query = mysqli_query($conn, "SELECT * FROM program_kerja WHERE id_program = " . intval($id_program));
$program = mysqli_fetch_assoc($query);

if (!$program) {
    die("Program tidak ditemukan.");
}

// Ambil nama departemen
$dep_query = mysqli_query($conn, "SELECT nama_departemen FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
$departemen = mysqli_fetch_assoc($dep_query);
$nama_departemen = $departemen['nama_departemen'] ?? 'Tidak diketahui';

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_program = $_POST["nama_program"];
    $deskripsi_program = $_POST["deskripsi_program"];

    $update = mysqli_query($conn, "UPDATE program_kerja 
        SET nama_program = '" . mysqli_real_escape_string($conn, $nama_program) . "', 
            deskripsi_program = '" . mysqli_real_escape_string($conn, $deskripsi_program) . "' 
        WHERE id_program = " . intval($id_program));

    if ($update) {
        header("Location: lihat_program.php?kode_departemen=" . urlencode($kode_departemen));
        exit();
    } else {
        $error = "Gagal mengupdate data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Program Kerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<main class="ml-64 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Edit Program Kerja</h2>
        <p class="text-sm text-gray-500 mb-6">Departemen: <strong><?= htmlspecialchars($nama_departemen) ?></strong></p>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Program</label>
                <input type="text" name="nama_program" value="<?= htmlspecialchars($program['nama_program']) ?>" required
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi_program" rows="4" required
                          class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:border-indigo-500"><?= htmlspecialchars($program['deskripsi_program']) ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                    Simpan Perubahan
                </button>
                <a href="lihat_program.php?kode_departemen=<?= urlencode($kode_departemen) ?>"
                   class="text-sm text-gray-600 hover:underline">← Kembali</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
