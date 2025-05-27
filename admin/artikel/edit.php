<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$errors = [];
$success = '';

$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("ID artikel tidak valid.");
}

// Ambil data artikel
$stmt = $conn->prepare("SELECT * FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$artikel = $result->fetch_assoc();
$stmt->close();

if (!$artikel) {
    die("Artikel tidak ditemukan.");
}

// Variabel untuk form
$judul = $artikel['judul'];
$deskripsi_artikel = $artikel['deskripsi_artikel'];
$isi = $artikel['isi'];
$gambar_lama = $artikel['gambar'];
$deskripsi_gambar = $artikel['deskripsi_gambar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi_artikel = trim($_POST['deskripsi_artikel'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $deskripsi_gambar = trim($_POST['deskripsi_gambar'] ?? '');

    if ($judul === '') $errors[] = 'Judul harus diisi.';
    if ($isi === '') $errors[] = 'Isi artikel harus diisi.';

    $gambar_filename = $gambar_lama;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error upload gambar, kode error: ' . $_FILES['gambar']['error'];
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_mime = $finfo->file($file_tmp);

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file_mime, $allowed_types)) {
                $errors[] = 'Tipe file gambar tidak diizinkan.';
            } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Ukuran gambar maksimal 2MB.';
            } else {
                $upload_dir = $root_path . '/img/artikel/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $new_name = time() . '_' . uniqid() . '.' . $ext;
                $target_file = $upload_dir . $new_name;

                if (move_uploaded_file($file_tmp, $target_file)) {
                    if ($gambar_lama && file_exists($upload_dir . $gambar_lama)) {
                        unlink($upload_dir . $gambar_lama); // hapus gambar lama
                    }
                    $gambar_filename = $new_name;
                } else {
                    $errors[] = 'Gagal memindahkan file gambar ke folder tujuan.';
                }
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE artikel SET judul = ?, deskripsi_artikel = ?, isi = ?, gambar = ?, deskripsi_gambar = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $judul, $deskripsi_artikel, $isi, $gambar_filename, $deskripsi_gambar, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $errors[] = 'Gagal memperbarui artikel: ' . htmlspecialchars($stmt->error);
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Artikel</title>
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
<body class="bg-gray-100">
<?php include_once '../sidebar.php'; ?>
<div class="ml-64 p-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Artikel</h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input
            type="text"
            name="judul"
            placeholder="Judul Artikel"
            required
            class="w-full p-2 border rounded"
            value="<?php echo htmlspecialchars($judul); ?>"
        />
        <textarea
            name="deskripsi_artikel"
            placeholder="Deskripsi Artikel"
            class="w-full p-2 border rounded"
        ><?php echo htmlspecialchars($deskripsi_artikel); ?></textarea>
        <textarea
            name="isi"
            placeholder="Isi Artikel"
            rows="6"
            required
            class="w-full p-2 border rounded"
        ><?php echo htmlspecialchars($isi); ?></textarea>

        <?php if ($gambar_lama): ?>
            <div>
                <p class="text-sm text-gray-600">Gambar saat ini:</p>
                <img src="/img/artikel/<?php echo htmlspecialchars($gambar_lama); ?>" class="w-40 mb-2 rounded shadow">
            </div>
        <?php endif; ?>

        <input
            type="file"
            name="gambar"
            accept="image/*"
            class="w-full"
        />
        <input
            type="text"
            name="deskripsi_gambar"
            placeholder="Deskripsi Gambar"
            class="w-full p-2 border rounded"
            value="<?php echo htmlspecialchars($deskripsi_gambar); ?>"
        />
        <div class="flex justify-end">
            <a href="index.php" class="mr-4 px-4 py-2 border border-gray-400 rounded hover:bg-gray-200">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Perbarui</button>
        </div>
    </form>
</div>
</body>
</html>
