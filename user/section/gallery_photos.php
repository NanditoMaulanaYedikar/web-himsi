<?php
$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';
if (!isset($_GET['folder_id'])) {
    echo "<script>alert('Folder ID tidak ditemukan .'); window.location='index.php';</script>";
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
    echo "<script>alert('Folder tidak ditemukan.'); window.location='gallery.php';</script>";
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
    <title>Gallery Photos - <?php echo htmlspecialchars($folder['nama_folder']); ?></title>
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
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
        }
        .modal-content {
            margin: 10% auto;
            display: block;
            max-width: 90%;
            max-height: 80vh;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 51;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen">
  <header class="px-6 py-4 flex justify-between items-center sticky top-0 z-30" style="background-color: #043464;">
    <h1 class="text-white font-semibold text-xl md:text-2xl">
      Gallery Photos: <?php echo htmlspecialchars($folder['nama_folder']); ?>
    </h1>
    <button
      type="button"
      class="flex items-center gap-2 text-gray-900 text-sm font-semibold rounded-lg px-5 py-2 shadow-md transition"
      style="background-color: #fead00;"
      onmouseover="this.style.backgroundColor='#e6a000'"
      onmouseout="this.style.backgroundColor='#fead00'"
      onclick="window.location.href='http://localhost/web-himsi/user/index.php'"
    >
      <i class="fas fa-arrow-left"></i>
      Back
    </button>
  </header>
    <main class="flex-grow max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 bg-white rounded shadow mt-6">
        <?php if (mysqli_num_rows($photos_result) === 0): ?>
            <p class="text-gray-600">No photos in this folder.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($photo = mysqli_fetch_assoc($photos_result)): ?>
                <div class="border rounded overflow-hidden shadow hover:shadow-lg transition cursor-pointer"
                    onclick="openModal('../../img/gallery_img/<?php echo htmlspecialchars($photo['nama_file']); ?>')">
                    <img src="../../img/gallery_img/<?= htmlspecialchars($photo['nama_file']) ?>"   
                            alt="<?php echo htmlspecialchars($photo['deskripsi'] ?? 'Photo'); ?>" 
                        class="w-full h-48 object-cover" />
                    <div class="p-4">
                        <p class="text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($photo['deskripsi'] ?? '')); ?></p>
                        <p class="text-xs text-gray-400 mt-2"><?php echo date('d M Y H:i', strtotime($photo['created_at'])); ?></p>
                        <a href="../../img/gallery_img/<?php echo htmlspecialchars($photo['nama_file']); ?>" 
                        download class="inline-block mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                        Download
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal for full image view -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage" />
    </div>

    <script>
        function openModal(src) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = src;
        }
        function closeModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
        // Prevent modal close when clicking on image
        document.getElementById("modalImage").addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
</body>
</html>
