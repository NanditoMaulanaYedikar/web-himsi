<?php
include '../config/db.php';

$kode = $_GET['kode_departemen'] ?? '';
if (!$kode) {
    die('Departemen tidak ditemukan.');
}

// Ambil data departemen
$sql = "SELECT * FROM departemen WHERE kode_departemen = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $kode);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$departemen = mysqli_fetch_assoc($result);

if (!$departemen) {
    die('Departemen tidak ditemukan.');
}

// Ambil anggota departemen
$anggota_query = "SELECT * FROM anggota WHERE kode_departemen = ?";
$stmt_anggota = mysqli_prepare($conn, $anggota_query);
mysqli_stmt_bind_param($stmt_anggota, "s", $kode);
mysqli_stmt_execute($stmt_anggota);
$anggota_result = mysqli_stmt_get_result($stmt_anggota);

// Ambil program kerja departemen
$proker_query = "SELECT * FROM program_kerja WHERE kode_departemen = ?";
$stmt_proker = mysqli_prepare($conn, $proker_query);
mysqli_stmt_bind_param($stmt_proker, "s", $kode);
mysqli_stmt_execute($stmt_proker);
$proker_result = mysqli_stmt_get_result($stmt_proker);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($departemen['nama_departemen']); ?> - Detail Departemen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<body class="font-poppins bg-gray-50 text-gray-900">

  <!-- Header -->
  <header class="bg-primary text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($departemen['nama_departemen']); ?></h1>
    <a href="http://localhost/web-himsi/user/index.php" class="bg-secondary hover:bg-yellow-600 text-sm font-semibold px-4 py-2 rounded flex items-center gap-2">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </header>

  <!-- Konten Utama -->
  <main class="max-w-6xl mx-auto py-10 px-6">
    <!-- Deskripsi -->
    <section class="mb-12">
      <h2 class="text-xl font-semibold text-primary mb-2">Deskripsi Departemen</h2>
      <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($departemen['deskripsi_departemen'])); ?></p>
    </section>

    <!-- Anggota -->
    <section class="mb-12">
      <h2 class="text-xl font-semibold text-primary mb-4">Anggota Departemen</h2>
      <?php if (mysqli_num_rows($anggota_result) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <?php while ($anggota = mysqli_fetch_assoc($anggota_result)): ?>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
              <?php if ($anggota['foto']): ?>
                <img src="../../img/anggota/<?php echo htmlspecialchars($anggota['foto']); ?>" alt="<?php echo htmlspecialchars($anggota['nama']); ?>" class="w-24 h-24 mx-auto rounded-full object-cover mb-2">
              <?php else: ?>
                <div class="w-24 h-24 mx-auto rounded-full bg-gray-200 flex items-center justify-center text-sm text-gray-500 mb-2">No Image</div>
              <?php endif; ?>
              <h3 class="font-semibold"><?php echo htmlspecialchars($anggota['nama']); ?></h3>
              <p class="text-sm text-gray-600"><?php echo htmlspecialchars($anggota['nim']); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-500">Belum ada anggota yang terdaftar.</p>
      <?php endif; ?>
    </section>

    <!-- Program Kerja -->
    <section>
      <h2 class="text-xl font-semibold text-primary mb-4">Program Kerja</h2>
      <?php if (mysqli_num_rows($proker_result) > 0): ?>
        <ul class="space-y-4">
          <?php while ($proker = mysqli_fetch_assoc($proker_result)): ?>
            <li class="bg-white shadow-md p-4 rounded">
              <h4 class="text-lg font-bold text-primary"><?php echo htmlspecialchars($proker['nama_program']); ?></h4>
              <p class="text-gray-700 mt-1"><?php echo nl2br(htmlspecialchars($proker['deskripsi_program'])); ?></p>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-500">Belum ada program kerja untuk departemen ini.</p>
      <?php endif; ?>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-6 mt-12">
    <p>&copy; <?php echo date('Y'); ?> HIMSI. All rights reserved.</p>
  </footer>
</body>
</html>
