<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fix relative path to config
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Kelola Departemen - Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
  <style>
   body {
    font-family: "Inter", sans-serif;
   }
   .table-container::-webkit-scrollbar {
    height: 8px;
   }
   .table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
   }
   .table-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
   }
   .table-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
   }
  </style>
 </head>
 <body class="bg-gray-100 min-h-screen flex flex-col">
  <?php include_once '../sidebar.php'; ?>
  <header class="bg-white shadow-md ml-64">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
    <div class="flex items-center space-x-3">
     <h1 class="text-xl font-semibold text-gray-800">Kelola Departemen</h1>
    </div>
    <a href="tambah.php" role="button" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition">
     <i class="fas fa-plus mr-2"></i>Tambah Departemen
    </a>
   </div>
  </header>
  <main class="flex-grow max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 ml-64">
   <div class="table-container overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 table-fixed">
     <thead class="bg-gray-50">
      <tr>
       <th class="w-12 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
       <th class="w-40 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Departemen</th>
       <th class="w-64 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Departemen</th>
       <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
       <th class="w-48 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
      </tr>
     </thead>
     <tbody class="bg-white divide-y divide-gray-200">
      <?php
      $no = 1;
      $query = mysqli_query($conn, "SELECT * FROM departemen ORDER BY kode_departemen ASC");
      while ($row = mysqli_fetch_assoc($query)) {
      ?>
      <tr class="hover:bg-gray-50 transition">
       <td class="px-4 py-4 text-sm text-center text-gray-900"><?php echo $no++; ?></td>
       <td class="px-4 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['kode_departemen']); ?></td>
       <td class="px-4 py-4 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
       <td class="px-4 py-4 text-sm text-gray-900 break-words max-w-xs"><?php echo nl2br(htmlspecialchars($row['deskripsi_departemen'] ?? '')); ?></td>
       <td class="px-4 py-4 text-sm text-center space-x-2 flex flex-wrap justify-center gap-2">
            <a href="edit.php?kode=<?php echo urlencode($row['kode_departemen']); ?>" class="inline-flex items-center px-3 py-1 bg-yellow-400 text-white rounded-md hover:bg-yellow-500">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
            <a href="hapus.php?kode=<?php echo urlencode($row['kode_departemen']); ?>" onclick="return confirm('Yakin mau hapus departemen ini?')" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                <i class="fas fa-trash-alt mr-1"></i>Hapus
            </a>
            <a href="program_kerja/lihat_program.php?kode_departemen=<?php echo urlencode($row['kode_departemen']); ?>" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-tasks mr-1"></i>Program
            </a>
            <a href="anggota/lihat_anggota.php?kode_departemen=<?php echo urlencode($row['kode_departemen']); ?>" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="fas fa-users mr-1"></i>Anggota
            </a>
        </td>
      </tr>
      <?php } ?>
     </tbody>
    </table>
   </div>
  </main>
 </body>
</html>
