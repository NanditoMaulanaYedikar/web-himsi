<?php
// Get latest news
$query = "SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3";
$result = mysqli_query($conn, $query);
?>

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Berita Terbaru</h2>
            <a href="news.php" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua Berita
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($news = mysqli_fetch_assoc($result)): ?>
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <?php if ($news['image']): ?>
                            <img src="asset/news/<?php echo htmlspecialchars($news['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['title']); ?>"
                                 class="w-full h-48 object-cover">
                        <?php endif; ?>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($news['title']); ?>
                            </h3>
                            <div class="text-sm text-gray-500 mb-4">
                                <span class="mr-4">
                                    <i class="fas fa-user mr-1"></i>
                                    <?php echo htmlspecialchars($news['author']); ?>
                                </span>
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    <?php echo date('d M Y', strtotime($news['created_at'])); ?>
                                </span>
                            </div>
                            <div class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo strip_tags($news['content']); ?>
                            </div>
                            <a href="news_detail.php?id=<?php echo $news['id']; ?>" 
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                Baca selengkapnya
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-gray-500 py-8">
                    Belum ada berita yang dipublikasikan
                </div>
            <?php endif; ?>
        </div>
    </div>
</section> 