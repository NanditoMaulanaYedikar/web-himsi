<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Path config database
include_once __DIR__ . '/../../../config/db.php';

$kode_departemen = $_GET['kode_departemen'] ?? '';
$nim = $_GET['nim'] ?? '';
$errors = [];
$anggota = null;

// Validasi dan ambil data departemen
if ($kode_departemen) {
    $res = mysqli_query($conn, "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
    $departemen = mysqli_fetch_assoc($res);
    if (!$departemen) $errors[] = "Departemen tidak ditemukan.";
} else {
    $errors[] = "Kode departemen tidak diberikan.";
}

// Ambil data anggota untuk di-edit berdasarkan nim & kode_departemen
if ($nim && $kode_departemen) {
    $res = mysqli_query($conn, "SELECT * FROM anggota WHERE nim = '" . mysqli_real_escape_string($conn, $nim) . "' AND kode_departemen = '" . mysqli_real_escape_string($conn, $kode_departemen) . "'");
    $anggota = mysqli_fetch_assoc($res);
    if (!$anggota) {
        $errors[] = "Anggota tidak ditemukan.";
    }
} else {
    $errors[] = "NIM anggota tidak diberikan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $nama = trim($_POST['nama'] ?? '');
    if (empty($nama)) $errors[] = "Nama wajib diisi.";

    $foto_filename = $anggota['foto']; // simpan foto lama jika tidak diganti

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
                    // Hapus file foto lama jika ada
                    if ($foto_filename && file_exists($upload_dir . $foto_filename)) {
                        unlink($upload_dir . $foto_filename);
                    }
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
        // Update data anggota
        $stmt = mysqli_prepare($conn, "UPDATE anggota SET nama = ?, foto = ? WHERE nim = ? AND kode_departemen = ?");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $foto_filename, $nim, $kode_departemen);
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
    <meta charset="UTF-8" />
    <title>Edit Anggota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include_once '../../sidebar.php'; ?>

    <div class="flex flex-col flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">
                    Edit Anggota Departemen: <?= htmlspecialchars($departemen['nama_departemen'] ?? '-') ?>
                </h1>
                <a href="lihat_anggota.php?kode_departemen=<?= urlencode($kode_departemen) ?>" class="text-sm text-blue-600 hover:underline">
                    &larr; Kembali
                </a>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-grow max-w-3xl mx-auto bg-white px-6 py-6 mt-6 rounded shadow">
            <?php if (!empty($errors)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($anggota): ?>
            <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                    <input type="text" name="nim" id="nim" readonly class="mt-1 block w-full border-gray-300 rounded shadow-sm bg-gray-100 cursor-not-allowed" value="<?= htmlspecialchars($anggota['nim']) ?>">
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" required class="mt-1 block w-full border-gray-300 rounded shadow-sm" value="<?= htmlspecialchars($_POST['nama'] ?? $anggota['nama']) ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Foto Saat Ini</label>
                    <?php if ($anggota['foto'] && file_exists(__DIR__ . '/../../../img/anggota/' . $anggota['foto'])): ?>
                        <img src="../../../img/anggota/<?= htmlspecialchars($anggota['foto']) ?>" alt="Foto Anggota" class="w-24 h-24 rounded-full object-cover border mt-2">
                    <?php else: ?>
                        <em class="text-gray-400">Tidak ada foto</em>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700">Ganti Foto (opsional)</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="mt-1 block w-full" />
                </div>
                <div class="flex justify-end">
                    <a href="lihat_anggota.php?kode_departemen=<?= urlencode($kode_departemen) ?>" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="ml-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
