<?php
$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

$query = "SELECT * FROM galeri_folder WHERE status = 'active' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!-- Gallery Section -->
<section id="gallery" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-primary mb-4">Gallery</h2>
            <div class="w-20 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="relative group overflow-hidden rounded-lg transform transition-transform duration-300 hover:scale-105" data-aos="fade-up">
                <?php if (!empty($row['thumbnail'])): ?>
                    <div class="h-64 bg-gray-200 bg-cover bg-center" style="background-image: url('<?php echo '../img/gallery_img/' . htmlspecialchars($row['thumbnail']); ?>')"></div>
                <?php else: ?>
                <div class="h-64 bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold rounded-lg">
                    No Image
                </div>
                <?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-primary to-transparent opacity-0 group-hover:opacity-90 transition-opacity duration-300 flex items-center justify-center">
                    <div class="text-white text-center p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                        <h4 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($row['nama_folder']); ?></h4>
                        <p class="text-sm"><?php echo nl2br(htmlspecialchars($row['deskripsi'] ?? '')); ?></p>
                        <a href="section/gallery_photos.php?folder_id=<?php echo $row['id']; ?>" class="mt-4 inline-block px-4 py-2 bg-secondary text-primary rounded-full text-sm font-semibold hover:bg-opacity-90 transition-colors duration-300" aria-label="View photos in <?php echo htmlspecialchars($row['nama_folder']); ?>">
                            View More
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
