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

if (!isset($_GET['event_id'])) {
    echo "<script>alert('ID event tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

$event_id = intval($_GET['event_id']); // sanitize event_id

if (isset($_POST['submit'])) {
    $nama_field = mysqli_real_escape_string($conn, $_POST['namaField']);
    $tipe_field = $_POST['tipeField'];
    $wajib = isset($_POST['wajibIsi']) ? 1 : 0;

    $insert = mysqli_query($conn, "INSERT INTO event_form_field (event_id, nama_field, tipe_field, wajib) 
                                   VALUES ('$event_id', '$nama_field', '$tipe_field', '$wajib')");
    if ($insert) {
        echo "<script>alert('Field berhasil ditambahkan!'); window.location='field.php?event_id=$event_id';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan field!');</script>";
    }
}

// Ambil daftar field yang sudah ada untuk event ini
$query = mysqli_query($conn, "SELECT * FROM event_form_field WHERE event_id = '$event_id'");
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Tambah Field Pendaftaran - Admin Panel</title>
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
  </style>
 </head>
 <body class="bg-gray-100 min-h-screen flex flex-col">
  <header class="bg-white shadow-md">
   <div
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16"
   >
    <div class="flex items-center space-x-3">
     <h1 class="text-xl font-semibold text-gray-800">
      Tambah Field Pendaftaran
     </h1>
    </div>
   </div>
  </header>
  <main class="flex-grow max-w-4xl mx-auto p-6 bg-white rounded-lg shadow mt-8 mb-12 space-y-10">
   <section>
    <form action="" method="POST" class="space-y-6 max-w-md">
     <div>
      <label
       for="namaField"
       class="block text-gray-700 font-semibold mb-2"
       >Nama Field:</label
      >
      <input
       type="text"
       id="namaField"
       name="namaField"
       placeholder="Masukkan nama field"
       required
       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
     </div>
     <div>
      <label
       for="tipeField"
       class="block text-gray-700 font-semibold mb-2"
       >Tipe Field:</label
      >
      <select
       id="tipeField"
       name="tipeField"
       required
       class="w-full border border-gray-300 rounded-md px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      >
       <option value="" disabled selected>
        Pilih tipe field
       </option>
       <option value="text">Text</option>
       <option value="number">Number</option>
       <option value="email">Email</option>
       <option value="date">Date</option>
       <option value="checkbox">Checkbox</option>
       <option value="textarea">Textarea</option>
       <option value="file">File</option>
      </select>
     </div>
     <div class="flex items-center space-x-3">
      <input
       type="checkbox"
       id="wajibIsi"
       name="wajibIsi"
       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
      />
      <label for="wajibIsi" class="text-gray-700 font-semibold"
       >Wajib di isi?</label
      >
     </div>
     <div class="flex space-x-4 pt-4">
      <button
       type="submit"
       name="submit"
       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition"
      >
       <i class="fas fa-plus mr-2"></i>Tambah Field
      </button>
      <a
       href="index.php"
       class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1 transition"
      >
       <i class="fas fa-arrow-left mr-2"></i>Kembali ke Event
      </a>
     </div>
    </form>
   </section>
   <section>
    <h2 class="text-xl font-semibold text-gray-800 mb-4">
     Daftar Field Pendaftaran
    </h2>
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow">
     <table class="min-w-full divide-y divide-gray-200 table-fixed bg-white">
      <thead class="bg-gray-50">
       <tr>
        <th
         scope="col"
         class="w-12 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        >
         No
        </th>
        <th
         scope="col"
         class="w-64 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        >
         Nama Field
        </th>
        <th
         scope="col"
         class="w-40 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
        >
         Tipe Field
        </th>
        <th
         scope="col"
         class="w-32 px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider"
        >
         Aksi
        </th>
       </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
       <?php
       $no = 1;
       while ($row = mysqli_fetch_assoc($query)) {
           echo "<tr class='hover:bg-gray-50 transition'>";
           echo "<td class='px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-center'>".$no++."</td>";
           echo "<td class='px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold'>".$row['nama_field']."</td>";
           echo "<td class='px-4 py-4 whitespace-nowrap text-sm text-gray-700'>".$row['tipe_field']."</td>";
           echo "<td class='px-4 py-4 whitespace-nowrap text-sm text-center'>";
           echo "<button aria-label='Hapus field ".$row['nama_field']."' class='inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 transition' type='button'> <i class='fas fa-trash-alt mr-1'></i>Hapus</button>";
           echo "</td>";
           echo "</tr>";
       }
       ?>
      </tbody>
     </table>
    </div>
   </section>
  </main>
 </body>
</html>
