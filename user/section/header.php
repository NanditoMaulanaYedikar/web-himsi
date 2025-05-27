<!-- Navigation -->
 <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/css/aos.css" rel="stylesheet">
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
<nav class="bg-primary shadow-lg fixed w-full z-50" id="mainNav">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <img src="../img/image/HIMSI.png" alt="HIMSI Logo" class="h-12 w-12">
                <div class="flex flex-col">
                    <a href="#" class="text-2xl font-bold text-white flex items-center">
                        <span class="text-secondary">HIM</span>SI
                    </a>
                    <span class="text-xs text-gray-300">Telkom University Jakarta</span>
                </div>
            </div>
            <div class="hidden md:flex space-x-4 lg:space-x-6">
                <a href="#about" class="nav-link text-white hover:text-secondary transition">About</a>
                <a href="#departments" class="nav-link text-white hover:text-secondary transition">Departments</a>
                <a href="#organization" class="nav-link text-white hover:text-secondary transition">Organization</a>
                <a href="#events" class="nav-link text-white hover:text-secondary transition">Events</a>
                <a href="#berita" class="nav-link text-white hover:text-secondary transition">Berita</a>
                <a href="#gallery" class="nav-link text-white hover:text-secondary transition">Gallery</a>
                <a href="#contact" class="nav-link text-white hover:text-secondary transition">Contact</a>
            </div>
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-white p-2" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden fixed inset-0 bg-primary bg-opacity-95 z-50">
                <div class="flex flex-col items-center justify-center h-full">
                    <button onclick="toggleMobileMenu()" class="absolute top-4 right-4 text-white p-2">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                    <div class="flex flex-col space-y-6 text-center">
                        <a href="#about" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">About</a>
                        <a href="#organization" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">Organization</a>
                        <a href="#departments" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">Departments</a>
                        <a href="#events" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">Events</a>
                        <a href="#gallery" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">Gallery</a>
                        <a href="#contact" class="text-white text-xl hover:text-secondary transition" onclick="toggleMobileMenu()">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
