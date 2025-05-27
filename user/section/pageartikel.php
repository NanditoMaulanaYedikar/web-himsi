<?php
include '../config/db.php';

// Pagination setup
$limit = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Hitung total artikel
$sql_count = "SELECT COUNT(*) AS total FROM artikel WHERE status = 'publish'";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_articles = $row_count['total'];
$total_pages = ceil($total_articles / $limit);

// Ambil artikel
$sql = "SELECT * FROM artikel WHERE status = 'publish' ORDER BY tanggal DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Artikel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://unpkg.com/aos@2.3.1/css/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
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
  </script>
</head>

<body class="min-h-screen flex flex-col font-poppins">
  <!-- Header -->
  <header class="px-6 py-4 flex justify-between items-center sticky top-0 z-30" style="background-color: #043464;">
    <h1 class="text-white font-semibold text-xl md:text-2xl">BERITA</h1>
    <button
      type="button"
      class="flex items-center gap-2 text-gray-900 text-sm font-semibold rounded-lg px-5 py-2 shadow-md transition"
      style="background-color: #fead00;"
      onmouseover="this.style.backgroundColor='#e6a000'"
      onmouseout="this.style.backgroundColor='#fead00'"
      onclick="window.location.href='http://localhost/web-himsi/user/index.php'">
      <i class="fas fa-arrow-left"></i>
      Back
    </button>
  </header>

  <!-- Main content wrapper -->
  <div class="flex-grow">
    <section class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-4xl font-bold mb-12 text-center text-primary">Berita</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <a href="artikel-detail.php?id=<?php echo $row['id']; ?>" class="block">
                <?php if (!empty($row['gambar'])): ?>
                  <img src="../../img/artikel/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" class="w-full h-48 object-cover" />
                <?php else: ?>
                  <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold">
                    No Image
                  </div>
                <?php endif; ?>
                <div class="p-6">
                  <h3 class="text-lg font-semibold mb-2 text-primary"><?php echo htmlspecialchars($row['judul']); ?></h3>
                  <p class="text-sm text-gray-700 line-clamp-3"><?php echo nl2br(htmlspecialchars($row['deskripsi_artikel'] ?? '')); ?></p>
                </div>
              </a>
            </article>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center col-span-3 text-gray-500">Tidak ada artikel untuk ditampilkan.</p>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <div class="mt-12 flex justify-center space-x-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <a href="?page=<?php echo $i; ?>" class="px-4 py-2 rounded <?php echo ($i === $page) ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100'; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </section>
  </div>

  <!-- Footer -->
  <footer class="bg-primary text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h3 class="text-2xl font-bold mb-2">HIMSI</h3>
        <p class="text-gray-300">Himpunan Mahasiswa Sistem Informasi</p>
        <p class="text-gray-400 text-sm">Universitas Tel U Jakarta</p>
        <div class="mt-4">
          <p class="text-gray-300">&copy; <?php echo date('Y'); ?> HIMSI. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button id="backToTop" class="fixed bottom-5 right-5 bg-secondary text-white p-3 rounded-full shadow-md hover:bg-yellow-600">
    <i class="fas fa-arrow-up"></i>
  </button>
</body>
<script>
  const backToTopButton = document.getElementById("backToTop");

  // Munculkan tombol saat scroll ke bawah
  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTopButton.classList.remove("hidden");
    } else {
      backToTopButton.classList.add("hidden");
    }
  });

  // Scroll halus ke atas saat diklik
  backToTopButton.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });
</script>
</html>
