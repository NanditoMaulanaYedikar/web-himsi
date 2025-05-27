<?php
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIMSI - Himpunan Mahasiswa Sistem Informasi</title>
    <!-- Tailwind CSS -->
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
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-50">
    <!-- Loading Screen -->
    <div class="loading" id="loading">
        <div class="loading-spinner"></div>
    </div>

    <?php
    // Include all sections with absolute paths
    $sections_path = __DIR__ . DIRECTORY_SEPARATOR . 'section' . DIRECTORY_SEPARATOR;
    include $sections_path . 'header.php';
    include $sections_path . 'hero.php';
    include $sections_path . 'about.php';
    include $sections_path . 'statistics.php';
    include $sections_path . 'departments.php';
    include $sections_path . 'organization.php';
    include $sections_path . 'events.php';
    include $sections_path . 'artikel.php';
    include $sections_path . 'gallery.php';
    include $sections_path . 'contact.php';
    include $sections_path . 'footer.php';
    ?>

    <script src="assets/js/script.js"></script>



</body>
</html>
