<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include __DIR__ . '/../../config/db.php';

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['namaEvent']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsiEvent']);
    $tanggal = $_POST['tanggalEvent'];
    $tempat = mysqli_real_escape_string($conn, $_POST['tempatEvent']);

    // Upload Gambar
    if (!empty($_FILES['gambarEvent']['name'])) {
        $gambar = $_FILES['gambarEvent']['name'];
        $tmp_name = $_FILES['gambarEvent']['tmp_name'];

        // Buat nama file unik biar gak bentrok
        $ext = pathinfo($gambar, PATHINFO_EXTENSION);
        $gambar_baru = uniqid() . '.' . $ext;

        // Pindahkan file ke folder asset
        move_uploaded_file($tmp_name, "../../img/event_img/" . $gambar_baru);

        // Insert ke database
        $insert = mysqli_query($conn, "INSERT INTO event (nama, deskripsi, tanggal, gambar, tempat) VALUES ('$nama', '$deskripsi', '$tanggal', '$gambar_baru', '$tempat')");

        if ($insert) {
            echo "<script>alert('Event berhasil ditambahkan!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan event.'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Gambar event wajib diupload.'); window.location='tambah.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Tambah Event - Admin Panel</title>
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
     <h1 class="text-xl font-semibold text-gray-800">Tambah Event</h1>
    </div>
   </div>
  </header>
  <main class="flex-grow max-w-3xl mx-auto p-6 bg-white rounded-lg shadow mt-8 mb-12">
   <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
    <div>
     <label
      for="namaEvent"
      class="block text-gray-700 font-semibold mb-2"
      >Nama Event:</label
     >
     <input
      type="text"
      id="namaEvent"
      name="namaEvent"
      placeholder="Masukkan nama event"
      required
      class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
     />
    </div>
    <div>
     <label
      for="deskripsiEvent"
      class="block text-gray-700 font-semibold mb-2"
      >Deskripsi Event:</label
     >
     <textarea
      id="deskripsiEvent"
      name="deskripsiEvent"
      placeholder="Masukkan deskripsi event"
      rows="4"
      required
      class="w-full border border-gray-300 rounded-md px-4 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
     ></textarea>
    </div>
    <div>
     <label
      for="tanggalEvent"
      class="block text-gray-700 font-semibold mb-2"
      >Tanggal Event:</label
     >
     <input
      type="date"
      id="tanggalEvent"
      name="tanggalEvent"
      required
      class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
     />
    </div>
    <div>
     <label
      for="tempatEvent"
      class="block text-gray-700 font-semibold mb-2"
      >Tempat Event:</label
     >
     <input
      type="text"
      id="tempatEvent"
      name="tempatEvent"
      placeholder="Masukkan tempat event"
      required
      class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
     />
    </div>
    <div>
     <label
      for="gambarEvent"
      class="block text-gray-700 font-semibold mb-2"
      >Gambar Event:</label
     >
     <input
      type="file"
      id="gambarEvent"
      name="gambarEvent"
      accept="image/*"
      required
      class="block w-full text-gray-700"
     />
    </div>
    <div class="flex justify-between items-center pt-4">
     <button
      type="submit"
      name="submit"
      class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition"
     >
      <i class="fas fa-save mr-2"></i>Simpan
     </button>
     <button
      type="button"
      onclick="history.back()"
      class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1 transition"
     >
      <i class="fas fa-arrow-left mr-2"></i>Kembali
     </button>
    </div>
   </form>
  </main>
 </body>
</html>
