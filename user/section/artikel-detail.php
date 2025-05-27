<?php
include '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: semua_artikel.php');
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM artikel WHERE id = ? AND status = 'publish' LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $artikel = null;
} else {
    $artikel = mysqli_fetch_assoc($result);

    $update_sql = "UPDATE artikel SET jumlah_dilihat = jumlah_dilihat + 1 WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "i", $id);
    mysqli_stmt_execute($update_stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $artikel ? htmlspecialchars($artikel['judul']) : 'Artikel Tidak Ditemukan'; ?> - HIMSI</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/css/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#013467',
            secondary: '#feac01',
          },
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          },
        }
      }
    }
    AOS.init();
  </script>

</head>
<body class="min-h-screen flex flex-col font-poppins bg-gray-50">

  <!-- Header -->
  <header class="px-6 py-4 flex justify-between items-center sticky top-0 z-30" style="background-color: #043464;">
    <h1 class="text-white font-semibold text-xl md:text-2xl">BERITA</h1>
    <button
      type="button"
      class="flex items-center gap-2 text-gray-900 text-sm font-semibold rounded-lg px-5 py-2 shadow-md transition"
      style="background-color: #fead00;"
      onmouseover="this.style.backgroundColor='#e6a000'"
      onmouseout="this.style.backgroundColor='#fead00'"
      onclick="window.history.back()">
      <i class="fas fa-arrow-left"></i>
      Back
    </button>
  </header>

  <main class="flex-grow w-full max-w-screen-xl mx-auto p-4 sm:p-8 md:p-10">
    <?php if ($artikel): ?>
      <article data-aos="fade-up" class="bg-white rounded-lg shadow-md p-6 sm:p-8 md:p-10 w-full">
        <h1 class="text-3xl font-bold mb-4 text-primary"><?php echo htmlspecialchars($artikel['judul']); ?></h1>
        <p class="text-sm text-gray-600 mb-6">
          <i class="fas fa-calendar-alt"></i>
          <?php echo date('d M Y', strtotime($artikel['tanggal'])); ?>
          &nbsp; | &nbsp;
          <i class="fas fa-user"></i>
          Himpunan Mahasiswa Sistem Informasi
          
        </p>

        <?php if (!empty($artikel['gambar'])): ?>
          <img
            src="../../img/artikel/<?php echo htmlspecialchars($artikel['gambar']); ?>"
            alt="<?php echo htmlspecialchars($artikel['judul']); ?>"
            class="w-full max-h-[500px] object-cover rounded mb-2"
          />
          <?php if (!empty($artikel['deskripsi_gambar'])): ?>
            <div class="flex items-center text-sm text-gray-500 italic mb-6 gap-2">
              <i class="fas fa-camera"></i>
              <p class="m-0">
                <?php echo htmlspecialchars($artikel['deskripsi_gambar']); ?>
              </p>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <div class="prose max-w-none text-gray-800 leading-relaxed">
          <?php echo nl2br(htmlspecialchars($artikel['isi'])); ?>
        </div>
      </article>
    <?php else: ?>
      <div class="text-center py-20">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Artikel tidak ditemukan.</h2>
        <a href="semua_artikel.php" class="inline-block px-6 py-2 bg-primary text-white rounded hover:bg-secondary transition">Kembali ke Berita</a>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer class="bg-primary text-white py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h3 class="text-2xl font-bold mb-2">HIMSI</h3>
      <p class="text-gray-300">Himpunan Mahasiswa Sistem Informasi</p>
      <p class="text-gray-400 text-sm">Universitas Tel U Jakarta</p>
      <p class="mt-4 text-gray-300">&copy; <?php echo date('Y'); ?> HIMSI. All rights reserved.</p>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button id="backToTop" class="fixed bottom-5 right-5 bg-secondary text-white p-3 rounded-full shadow-md hover:bg-yellow-600 hidden">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    const backToTopButton = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        backToTopButton.classList.remove("hidden");
      } else {
        backToTopButton.classList.add("hidden");
      }
    });
    backToTopButton.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  </script>

</body>
</html>
