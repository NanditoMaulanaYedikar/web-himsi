<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Kelola Artikel - Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
   href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
   rel="stylesheet"
  />
  <link
   href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap"
   rel="stylesheet"
  />
  <style>
   body {
    font-family: "Inter", sans-serif;
   }
   /* Custom scrollbar for table container */
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
   <div
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16"
   >
    <div class="flex items-center space-x-3">
     <h1 class="text-xl font-semibold text-gray-800">Kelola Artikel</h1>
    </div>
    <a href="tambah.php" role="button" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition" id="btnTambahAtikel">
     <i class="fas fa-plus mr-2"></i>Tambah Artikel
    </a>
   </div>
  </header>
  <main class="flex-grow max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 ml-64">
   <div
    class="table-container overflow-x-auto bg-white rounded-lg shadow border border-gray-200"
   >
    <table class="min-w-full divide-y divide-gray-200 table-fixed">
     <thead class="bg-gray-50">
      <tr>
       <th
        class="w-12 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        No
       </th>
       <th
        class="w-28 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Gambar
       </th>
       <th
        class="w-48 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        judul
       </th>
       <th
        class="w-96 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Deskripsi
       </th>
       <th
        class="w-96 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Total View
       </th>
       <th
        class="w-28 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Status
       </th>
       <th
        class="w-48 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Aksi
       </th>
      </tr>
     </thead>
      <tbody class="bg-white divide-y divide-gray-200" id="ArtikelTableBody">
       <?php
       $no = 1;
       $query = mysqli_query($conn, "SELECT * FROM artikel ORDER BY tanggal DESC");
       while ($row = mysqli_fetch_assoc($query)) {
       ?>
       <tr class="hover:bg-gray-50 transition" data-id="<?php echo $row['id']; ?>">
        <td
         class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-center"
        >
         <?php echo $no++; ?>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
         <?php if (!empty($row['gambar'])): ?>
          <img
           alt="gambar for artikel <?php echo htmlspecialchars($row['judul']); ?>"
           src="<?php echo '../../img/artikel/' . htmlspecialchars($row['gambar']); ?>"
           class="w-20 h-20 object-cover rounded-md"
          />
         <?php else: ?>
          <div class="w-20 h-20 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs font-semibold select-none">
           No Gambar
          </div>
         <?php endif; ?>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
            <?php
                $judul = $row['judul'] ?? '';
                $words = explode(' ', strip_tags($judul));
                $limited = implode(' ', array_slice($words, 0, 5));
                echo htmlspecialchars($limited . (count($words) > 5 ? '...' : ''));
            ?>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 break-words max-w-xs">
            <?php
                $deskripsi = $row['deskripsi_artikel'] ?? '';
                $words = explode(' ', strip_tags($deskripsi)); 
                $limited = implode(' ', array_slice($words, 0, 5)); 
                echo htmlspecialchars($limited . (count($words) > 5 ? '...' : ''));
            ?>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 break-words max-w-xs">
            <?php
                echo htmlspecialchars($row['jumlah_dilihat']);
            ?>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
         <?php if ($row['status'] === 'publish'): ?>
          <a href="toggle_status.php?artikel_id=<?php echo $row['id']; ?>" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200" aria-label="Deactivate artikel <?php echo htmlspecialchars($row['judul']); ?>">
           Publish
          </a>
         <?php else: ?>
          <a href="toggle_status.php?artikel_id=<?php echo $row['id']; ?>" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300" aria-label="Activate artikel <?php echo htmlspecialchars($row['judul']); ?>">
           Nopublish
          </a>
         <?php endif; ?>
        </td>
        <td
         class="px-4 py-4 whitespace-nowrap text-sm text-center space-x-2 flex flex-wrap justify-center gap-2"
        >
         <a
          href="edit.php?id=<?php echo $row['id']; ?>"
          aria-label="Edit folder <?php echo htmlspecialchars($row['judul']); ?>"
          class="inline-flex items-center px-3 py-1 bg-yellow-400 text-white rounded-md hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-1 transition"
         >
          <i class="fas fa-edit mr-1"></i>Edit
         </a>
         <a
          href="hapus.php?id=<?php echo $row['id']; ?>"
          onclick="return confirm('Yakin mau hapus?')"
          aria-label="Hapus folder <?php echo htmlspecialchars($row['judul']); ?>"
          class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 transition"
         >
          <i class="fas fa-trash-alt mr-1"></i>Hapus
         </a>
        </td>
       </tr>
       <?php
       }
       ?>
      </tbody>
    </table>
   </div>
  </main>
 </body>
</html>
