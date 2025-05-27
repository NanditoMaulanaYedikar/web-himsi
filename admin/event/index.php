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
  <title>Kelola Event - Admin Panel</title>
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
   .status-toggle {
    @apply inline-flex rounded-full border border-gray-300 bg-white p-1;
   }
   .status-toggle button {
    @apply px-3 py-1 text-xs font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition;
   }
   .status-toggle button.active {
    @apply bg-blue-600 text-white border-transparent;
   }
   .status-toggle button:not(.active):hover {
    @apply bg-gray-100;
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
     <h1 class="text-xl font-semibold text-gray-800">Kelola Event</h1>
    </div>
    <a href="tambah.php" role="button" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition" id="btnTambahEvent">
     <i class="fas fa-plus mr-2"></i>Tambah Event
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
        class="w-36 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Nama Event
       </th>
       <th
        class="w-96 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Deskripsi
       </th>
       <th
        class="w-28 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Tanggal
       </th>
       <th
        class="w-36 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Gambar
       </th>
       <th
        class="w-28 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Status
       </th>
       <th
        class="w-64 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
        scope="col"
       >
        Aksi
       </th>
      </tr>
     </thead>
     <tbody class="bg-white divide-y divide-gray-200" id="eventTableBody">
      <?php
      $no = 1;
      $query = mysqli_query($conn, "SELECT * FROM event ORDER BY created_at DESC");
      while ($row = mysqli_fetch_assoc($query)) {
      ?>
      <tr class="hover:bg-gray-50 transition" data-id="<?php echo $row['id']; ?>" data-status="<?php echo $row['status']; ?>">
       <td
        class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-center"
       >
        <?php echo $no++; ?>
       </td>
       <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
        <?php echo htmlspecialchars($row['nama']); ?>
       </td>
       <td
        class="px-4 py-4 max-w-xs text-sm text-gray-700 break-words"
        title="<?php echo htmlspecialchars($row['deskripsi']); ?>"
       >
        <?php echo htmlspecialchars($row['deskripsi']); ?>
       </td>
       <td
        class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-mono text-center"
       >
        <?php echo htmlspecialchars($row['tanggal']); ?>
       </td>
       <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
        <?php if (empty($row['gambar'])): ?>
        <div
         class="w-28 h-20 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs font-semibold select-none"
        >
         No image available for event <?php echo htmlspecialchars($row['nama']); ?>
        </div>
        <?php else: ?>
        <img
         alt="Image for event <?php echo htmlspecialchars($row['nama']); ?>"
         class="w-28 h-20 object-cover rounded-md"
         height="160"
         src="<?php echo '../../img/event_img/' . htmlspecialchars($row['gambar']); ?>"
         width="224"
        />
        <?php endif; ?>
       </td>
       <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
        <div>
         <?php if ($row['status'] === 'open'): ?>
          <a href="toggle_status.php?event_id=<?php echo $row['id']; ?>" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-600 text-white hover:bg-blue-700 transition" aria-label="Tutup pendaftaran event <?php echo htmlspecialchars($row['nama']); ?>">
           <i class="fas fa-lock mr-1"></i>Tutup
          </a>
         <?php else: ?>
          <a href="toggle_status.php?event_id=<?php echo $row['id']; ?>" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition" aria-label="Buka pendaftaran event <?php echo htmlspecialchars($row['nama']); ?>">
           <i class="fas fa-unlock mr-1"></i>Open
          </a>
         <?php endif; ?>
        </div>
       </td>
       <td
        class="px-4 py-4 whitespace-nowrap text-sm text-center space-x-2 flex flex-wrap justify-center gap-2"
       >
        <a
         href="edit.php?id=<?php echo $row['id']; ?>"
         aria-label="Edit event <?php echo htmlspecialchars($row['nama']); ?>"
         class="inline-flex items-center px-3 py-1 bg-yellow-400 text-white rounded-md hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-1 transition"
        >
         <i class="fas fa-edit mr-1"></i>Edit
        </a>
        <a
         href="hapus.php?id=<?php echo $row['id']; ?>"
         onclick="return confirm('Yakin mau hapus?')"
         aria-label="Hapus event <?php echo htmlspecialchars($row['nama']); ?>"
         class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 transition"
        >
         <i class="fas fa-trash-alt mr-1"></i>Hapus
        </a>
        <a
         href="field.php?event_id=<?php echo $row['id']; ?>"
         aria-label="Kelola field event <?php echo htmlspecialchars($row['nama']); ?>"
         class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-1 transition"
        >
         <i class="fas fa-sliders-h mr-1"></i>Kelola Field
        </a>
        <a
         href="registrations.php?event_id=<?php echo $row['id']; ?>"
         aria-label="Lihat peserta event <?php echo htmlspecialchars($row['nama']); ?>"
         class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-1 transition"
        >
         <i class="fas fa-users mr-1"></i>Lihat Peserta
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
  <script>
   // Toggle status buttons group behavior
   document.querySelectorAll(".status-toggle").forEach((group) => {
    const buttons = group.querySelectorAll("button");
    buttons.forEach((btn) => {
     btn.addEventListener("click", () => {
      // If already active, do nothing
      if (btn.classList.contains("active")) return;

      // Remove active from all buttons
      buttons.forEach((b) => {
       b.classList.remove("active", "bg-blue-600", "text-white", "border-transparent");
       b.classList.add("text-gray-600", "border", "border-gray-300");
       b.setAttribute("aria-pressed", "false");
      });

      // Add active to clicked button
      btn.classList.add("active", "bg-blue-600", "text-white", "border-transparent");
      btn.classList.remove("text-gray-600", "border", "border-gray-300");
      btn.setAttribute("aria-pressed", "true");

      // Update the row data-status attribute accordingly
      const tr = btn.closest("tr");
      if (!tr) return;
      if (btn.textContent.trim().toLowerCase() === "open") {
       tr.setAttribute("data-status", "open");
      } else if (btn.textContent.trim().toLowerCase() === "tutup") {
       tr.setAttribute("data-status", "closed");
      }
     });
    });
   });
  </script>
 </body>
</html>
