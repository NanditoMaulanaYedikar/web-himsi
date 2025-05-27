<!-- Hero Section -->
 <?php
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<section id="hero" class="hero-pattern min-h-screen flex items-center justify-center pt-16 relative overflow-hidden">
    <!-- Animated background particles -->
    <div class="absolute inset-0">
        <div class="particles-container"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
        <div class="text-center hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 hero-title tracking-tight">
                Himpunan Mahasiswa <span class="text-secondary">Sistem Informasi</span>
            </h1>
            <div class="text-xl text-gray-300 mb-4 font-light">
                <span id="typed"></span>
            </div>
            <p class="text-lg text-gray-400 mb-8">
                Telkom University Jakarta
            </p>
            <div class="space-x-4 flex flex-col sm:flex-row justify-center gap-4 sm:gap-0">
                <a href="#about" class="bg-secondary text-primary px-8 py-3 rounded-full font-semibold hover:bg-opacity-90 transition transform hover:scale-105 duration-300 shadow-lg">
                    Explore More
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#contact" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-primary transition transform hover:scale-105 duration-300">
                    Contact Us
                    <i class="fas fa-envelope ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
    </div>
</section>
