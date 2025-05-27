<?php
include __DIR__ . '/config/db.php';

// Get upcoming events
$query = mysqli_query($conn, "SELECT * FROM event WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 3");

if ($query && mysqli_num_rows($query) > 0) {
    while ($event = mysqli_fetch_assoc($query)) {
        $date = date('M d', strtotime($event['tanggal']));
        ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
            <div class="relative">
                <img src="asset/event_img/<?php echo htmlspecialchars($event['gambar']); ?>" alt="<?php echo htmlspecialchars($event['nama']); ?>" class="w-full h-64 object-cover">
                <div class="absolute top-4 right-4 bg-secondary text-primary px-3 py-1 rounded-full text-sm font-semibold">
                    <?php echo $date; ?>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold text-primary mb-2"><?php echo htmlspecialchars($event['nama']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['deskripsi']); ?></p>
                <div class="flex flex-col space-y-3">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt mr-2 text-secondary"></i>
                        <span>Kampus C</span>
                    </div>
                    <?php if ($event['status'] == 'open'): ?>
                        <a href="pendaftaraan.php?event_id=<?php echo $event['id']; ?>" class="w-full bg-secondary text-primary py-2 rounded-full font-semibold hover:bg-opacity-90 transition-colors duration-300 flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Daftar Sekarang
                        </a>
                    <?php else: ?>
                        <div class="w-full bg-red-500 text-white py-2 rounded-full font-semibold text-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            Pendaftaran Ditutup
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="text-center text-gray-600">No upcoming events.</div>';
}
?>
