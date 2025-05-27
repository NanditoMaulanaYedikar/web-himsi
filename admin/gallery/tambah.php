<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_folder = trim($_POST['nama_folder'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($nama_folder)) {
        $errors[] = 'Nama folder harus diisi.';
    }

    $thumbnail_filename = null;
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
                    $thumbnail_filename = $new_name;
                } else {
                    $errors[] = 'Gagal mengupload thumbnail.';
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

        $sql = "INSERT INTO galeri_folder (nama_folder, deskripsi, thumbnail, created_at)
                VALUES ('$nama_folder_escaped', '$deskripsi_escaped', $thumbnail_escaped, NOW())";
        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Tambah Gallery - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap"
        rel="stylesheet"
    />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include_once '../sidebar.php'; 
    ?>
    <header class="bg-white shadow-md ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <h1 class="text-xl font-semibold text-gray-800">Tambah Gallery</h1>
        </div>
        
    </header>
    <main class="flex-grow max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 ml-64 bg-white rounded shadow">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="nama_folder" class="block text-sm font-medium text-gray-700">Nama Folder</label>
                <input type="text" name="nama_folder" id="nama_folder" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
            </div>
            <div>
                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail Foto</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-1 block w-full" />
            </div>
            <div class="flex justify-end">
                <a href="index.php" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">Simpan</button>
            </div>
        </form>
    </main>
</body>
</html>
