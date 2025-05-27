<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

if (!isset($_GET['folder_id'])) {
    echo "<script>alert('Folder ID tidak ditemukan.'); window.location='../gallery/index.php';</script>";
    exit;
}

$folder_id = intval($_GET['folder_id']);

// Fetch folder info
$folder_query = mysqli_prepare($conn, "SELECT * FROM galeri_folder WHERE id = ?");
mysqli_stmt_bind_param($folder_query, 'i', $folder_id);
mysqli_stmt_execute($folder_query);
$folder_result = mysqli_stmt_get_result($folder_query);
$folder = mysqli_fetch_assoc($folder_result);
mysqli_stmt_close($folder_query);

if (!$folder) {
    echo "<script>alert('Folder tidak ditemukan.'); window.location='../gallery/index.php';</script>";
    exit;
}

// Fetch photos in folder
$photos_query = mysqli_prepare($conn, "SELECT * FROM galeri_foto WHERE galeri_folder_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($photos_query, 'i', $folder_id);
mysqli_stmt_execute($photos_query);
$photos_result = mysqli_stmt_get_result($photos_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Foto Gallery - <?php echo htmlspecialchars($folder['nama_folder']); ?></title>
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
            <h1 class="text-xl font-semibold text-gray-800">Foto Gallery: <?php echo htmlspecialchars($folder['nama_folder']); ?></h1>
            <a href="../gallery/index.php" class="inline-flex items-center px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Gallery
            </a>
            <a href="tambah.php?folder_id=<?php echo $folder_id; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 ml-4">
                <i class="fas fa-plus mr-2"></i> Tambah Foto
            </a>
        </div>
    </header>
    <main class="flex-grow max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 ml-64 bg-white rounded shadow">
        <?php if (mysqli_num_rows($photos_result) === 0): ?>
            <p class="text-gray-600">Belum ada foto di folder ini.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($photo = mysqli_fetch_assoc($photos_result)): ?>
                    <div class="border rounded overflow-hidden shadow hover:shadow-lg transition">
                        <img src="<?php echo '../../img/gallery_img/' . htmlspecialchars($photo['nama_file']); ?>" alt="<?php echo htmlspecialchars($photo['deskripsi'] ?? 'Foto'); ?>" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <p class="text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($photo['deskripsi'] ?? '')); ?></p>
                            <p class="text-xs text-gray-400 mt-2"><?php echo date('d M Y H:i', strtotime($photo['created_at'])); ?></p>
                             
                        </div>
                        <a
                    
                                href="hapus.php?folder_id=<?php echo $folder_id; ?>&delete_id=<?php echo $photo['id']; ?>"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?');"
                                class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 m-3"
                            >
                                Hapus
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
