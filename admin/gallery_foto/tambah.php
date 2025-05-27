<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi array error
$errors = [];

// Dapatkan folder_id dari URL GET dan validasi
if (!isset($_GET['folder_id']) || !is_numeric($_GET['folder_id'])) {
    echo "<script>alert('Folder ID tidak ditemukan atau tidak valid.'); window.location='index.php';</script>";
    exit;
}
$folder_id = (int) $_GET['folder_id'];

// Tentukan root path dan include koneksi database
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php'; // Pastikan di file ini ada koneksi $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gunakan mysqli_real_escape_string dengan memastikan $conn sudah terdefinisi
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, $_POST['deskripsi']) : '';

    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Foto harus diupload.';
    } elseif ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Terjadi kesalahan saat mengupload file.';
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $allowed_types)) {
            $errors[] = 'Tipe file tidak diizinkan. Hanya JPG, PNG, GIF yang diperbolehkan.';
        } else {
            $upload_dir = $root_path . '/img/gallery_img/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            try {
                $random_name = bin2hex(random_bytes(8)); // 16 karakter acak
            } catch (Exception $e) {
                // fallback jika random_bytes gagal
                $random_name = uniqid('', true);
            }
            $filename = time() . '_' . $random_name . '.' . $ext;
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $sql = "INSERT INTO galeri_foto (galeri_folder_id, nama_file, deskripsi, created_at) VALUES (?, ?, ?, NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt === false) {
                    $errors[] = 'Gagal menyiapkan query database.';
                } else {
                    mysqli_stmt_bind_param($stmt, 'iss', $folder_id, $filename, $deskripsi);
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        header("Location: index.php?folder_id=$folder_id");
                        exit;
                    } else {
                        $errors[] = 'Gagal menyimpan data ke database.';
                    }
                }
            } else {
                $errors[] = 'Gagal mengupload file.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Tambah Foto - Admin Panel</title>
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
    <?php include_once '../sidebar.php'; ?>
    <header class="bg-white shadow-md ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <h1 class="text-xl font-semibold text-gray-800">Tambah Foto</h1>
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
                <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/gif" required class="mt-1 block w-full" />
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
            </div>
            <div class="flex justify-end">
                <a href="index.php?folder_id=<?php echo $folder_id; ?>" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">Simpan</button>
            </div>
        </form>
    </main>
</body>
</html>
