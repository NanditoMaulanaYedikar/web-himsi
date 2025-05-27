<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: index.php");
    exit();
}

$errors = [];
$query = mysqli_query($conn, "SELECT * FROM galeri_folder WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_folder = trim($_POST['nama_folder'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($nama_folder)) {
        $errors[] = 'Nama folder harus diisi.';
    }

    $thumbnail_filename = $data['thumbnail']; // default: tetap
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['thumbnail']['type'];
            if (!in_array($file_type, $allowed_types)) {
                $errors[] = 'Tipe file thumbnail tidak diizinkan.';
            } else {
                $upload_dir = $root_path . '/img/gallery_img/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                $new_name = time() . '_' . uniqid() . '.' . $ext;
                $target_file = $upload_dir . $new_name;

                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
                    // Hapus file lama jika ada
                    if ($data['thumbnail'] && file_exists($upload_dir . $data['thumbnail'])) {
                        unlink($upload_dir . $data['thumbnail']);
                    }
                    $thumbnail_filename = $new_name;
                } else {
                    $errors[] = 'Gagal upload thumbnail.';
                }
            }
        } else {
            $errors[] = 'Error saat upload.';
        }
    }

    if (empty($errors)) {
        $nama_folder_escaped = mysqli_real_escape_string($conn, $nama_folder);
        $deskripsi_escaped = mysqli_real_escape_string($conn, $deskripsi);
        $thumbnail_escaped = $thumbnail_filename ? "'" . mysqli_real_escape_string($conn, $thumbnail_filename) . "'" : "NULL";

        $sql = "UPDATE galeri_folder SET 
                    nama_folder = '$nama_folder_escaped', 
                    deskripsi = '$deskripsi_escaped', 
                    thumbnail = $thumbnail_escaped 
                WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = 'Gagal menyimpan perubahan.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Gallery - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: "Inter", sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include_once '../sidebar.php'; ?>
    <header class="bg-white shadow-md ml-64">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <h1 class="text-xl font-semibold text-gray-800">Edit Gallery</h1>
        </div>
    </header>
    <main class="flex-grow max-w-3xl mx-auto p-4 ml-64 bg-white rounded shadow">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="nama_folder" class="block text-sm font-medium text-gray-700">Nama Folder</label>
                <input type="text" name="nama_folder" id="nama_folder" value="<?= htmlspecialchars($folder['nama_folder']) ?>" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"><?= htmlspecialchars($folder['deskripsi']) ?></textarea>
            </div>
            <div>
                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail Baru (Opsional)</label>
                <?php if (!empty($folder['thumbnail'])): ?>
                    <img src="../../img/gallery_img/<?= htmlspecialchars($folder['thumbnail']) ?>" alt="Thumbnail lama" class="w-32 mb-2 rounded" />
                <?php endif; ?>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-1 block w-full" />
            </div>
            <div class="flex justify-end">
                <a href="index.php" class="mr-4 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Perbarui</button>
            </div>
        </form>
    </main>
</body>
</html>
