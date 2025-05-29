<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include_once __DIR__ . '/../../../config/db.php';

$kode_departemen = $_GET['kode_departemen'] ?? '';
$errors = [];

// Cek validitas departemen
$departemen = null;
if ($kode_departemen) {
    $res = mysqli_query($conn, "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
    $departemen = mysqli_fetch_assoc($res);
    if (!$departemen) $errors[] = "Departemen tidak ditemukan.";
} else {
    $errors[] = "Kode departemen tidak diberikan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');

    if (empty($nim)) $errors[] = "NIM wajib diisi.";
    if (empty($nama)) $errors[] = "Nama wajib diisi.";

    $foto_filename = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $file_type = $_FILES['foto']['type'];
            if (!in_array($file_type, $allowed_types)) {
                $errors[] = 'Tipe file foto tidak diizinkan.';
            } else {
                $upload_dir = __DIR__ . '/../../../img/anggota/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $new_name = time() . '_' . uniqid() . '.' . $ext;
                $target_file = $upload_dir . $new_name;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                    $foto_filename = $new_name;
                } else {
                    $errors[] = 'Gagal mengupload foto.';
                }
            }
        } else {
            $errors[] = 'Terjadi kesalahan saat upload.';
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO anggota (nim, nama, foto, kode_departemen) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nim, $nama, $foto_filename, $kode_departemen);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: lihat_anggota.php?kode_departemen=" . urlencode($kode_departemen));
            exit();
        } else {
            $errors[] = "Gagal menyimpan data ke database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Anggota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include_once '../../sidebar.php'; ?>

    <div class="flex-1 ml-64">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">Tambah Anggota - <?= htmlspecialchars($departemen['nama_departemen'] ?? '-') ?></h1>
                <a href="lihat_anggota.php?kode_departemen=<?= urlencode($kode_departemen) ?>" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
            </div>
        </header>

        <main class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow">
            <?php if (!empty($errors)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                    <input type="text" name="nim" id="nim" class="mt-1 block w-full border-gray-300 rounded shadow-sm" required value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>">
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" class="mt-1 block w-full border-gray-300 rounded shadow-sm" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                </div>
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="mt-1 block w-full">
                </div>
                <div class="flex justify-end">
                    <a href="lihat_anggota.php?kode_departemen=<?= urlencode($kode_departemen) ?>" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="ml-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
