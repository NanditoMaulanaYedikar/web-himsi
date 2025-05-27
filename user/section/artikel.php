<?php
$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

$query = "SELECT * FROM artikel WHERE status = 'publish' ORDER BY tanggal DESC LIMIT 3"; // Batasi untuk preview saja
$result = mysqli_query($conn, $query);
?>

<!-- Articles Section -->
<section id="berita" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-primary mb-4">Berita</h2>
            <div class="w-20 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <article class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform duration-300 hover:scale-105" data-aos="fade-up">
                <?php if (!empty($row['gambar'])): ?>
                <div class="h-48 bg-gray-200 bg-cover bg-center" style="background-image: url('<?php echo '../img/artikel/' . htmlspecialchars($row['gambar']); ?>')"></div>
                <?php else: ?>
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold">
                    No Image
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2 text-primary"><?php echo htmlspecialchars($row['judul']); ?></h3>
                    <p class="text-sm text-gray-700 mb-4 line-clamp-3"><?php echo nl2br(htmlspecialchars($row['deskripsi_artikel'] ?? '')); ?></p>
                    <a href="section/artikel-detail.php?id=<?php echo $row['id']; ?>" 
                        class="inline-block px-4 py-2 bg-secondary text-primary rounded-full text-sm font-semibold hover:bg-opacity-90 transition-colors duration-300" 
                        aria-label="Read more about <?php echo htmlspecialchars($row['judul']); ?>">
                            Read More
                    </a>


                </div>
            </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Button Selengkapnya di pojok kanan bawah -->
        <div class="mt-8 flex justify-end">
            <a href="section/pageartikel.php" style="color: black;" class="bg-secondary inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-full text-sm font-semibold hover:bg-opacity-90 transition-colors duration-300" aria-label="Lihat semua artikel">
                Selengkapnya
                <span class="ml-2">→</span>
            </a>
        </div>
    </div>
</section>
