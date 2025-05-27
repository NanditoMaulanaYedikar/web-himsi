<!-- Impact in Numbers Section -->
<section id="statistics" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-primary mb-4">Impact in Numbers</h2>
            <div class="w-20 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <?php
            // Get actual counts from database
            $member_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM peserta"))['count'] ?? 0;
            $event_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM event"))['count'] ?? 0;
            
            $stats = [
                [
                    'icon' => 'users',
                    'count' => $member_count,
                    'label' => 'Active Members'
                ],
                [
                    'icon' => 'calendar-alt',
                    'count' => $event_count,
                    'label' => 'Events'
                ],
                [
                    'icon' => 'trophy',
                    'count' => 5,
                    'label' => 'Awards'
                ]
            ];

            foreach ($stats as $index => $stat) {
                ?>
                <div class="stat-card bg-white p-8 rounded-xl shadow-lg transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl" 
                     data-aos="fade-up" 
                     data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="text-center">
                        <i class="fas fa-<?php echo $stat['icon']; ?> text-4xl text-secondary mb-4"></i>
                        <h3 class="text-4xl font-bold text-primary mb-2">
                            <span class="counter" data-target="<?php echo $stat['count']; ?>">0</span>
                        </h3>
                        <p class="text-lg text-gray-600"><?php echo $stat['label']; ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
