<<<<<<< HEAD
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_departemen = trim($_POST['kode_departemen'] ?? '');
    $nama_departemen = trim($_POST['nama_departemen'] ?? '');
    $deskripsi_departemen = trim($_POST['deskripsi_departemen'] ?? '');

    if (empty($kode_departemen)) {
        $errors[] = 'Kode departemen harus diisi.';
    }

    if (empty($nama_departemen)) {
        $errors[] = 'Nama departemen harus diisi.';
    }

    if (empty($errors)) {
        $kode_escaped = mysqli_real_escape_string($conn, $kode_departemen);
        $nama_escaped = mysqli_real_escape_string($conn, $nama_departemen);
        $deskripsi_escaped = mysqli_real_escape_string($conn, $deskripsi_departemen);

        $query = "INSERT INTO departemen (kode_departemen, nama_departemen, deskripsi_departemen)
                  VALUES ('$kode_escaped', '$nama_escaped', '$deskripsi_escaped')";

        if (mysqli_query($conn, $query)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Departemen - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include_once '../sidebar.php'; ?>

    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-10 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">Tambah Departemen</h1>
        </div>
    </header>

    <!-- Main content -->
    <main class="pt-20 pl-72 pr-6 pb-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
                <div>
                    <label for="kode_departemen" class="block text-sm font-medium text-gray-700">Kode Departemen</label>
                    <input type="text" name="kode_departemen" id="kode_departemen" required
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="nama_departemen" class="block text-sm font-medium text-gray-700">Nama Departemen</label>
                    <input type="text" name="nama_departemen" id="nama_departemen" required
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="deskripsi_departemen" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi_departemen" id="deskripsi_departemen" rows="3"
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="index.php"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

=======
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_departemen = trim($_POST['kode_departemen'] ?? '');
    $nama_departemen = trim($_POST['nama_departemen'] ?? '');
    $deskripsi_departemen = trim($_POST['deskripsi_departemen'] ?? '');

    if (empty($kode_departemen)) {
        $errors[] = 'Kode departemen harus diisi.';
    }

    if (empty($nama_departemen)) {
        $errors[] = 'Nama departemen harus diisi.';
    }

    if (empty($errors)) {
        $kode_escaped = mysqli_real_escape_string($conn, $kode_departemen);
        $nama_escaped = mysqli_real_escape_string($conn, $nama_departemen);
        $deskripsi_escaped = mysqli_real_escape_string($conn, $deskripsi_departemen);

        $query = "INSERT INTO departemen (kode_departemen, nama_departemen, deskripsi_departemen)
                  VALUES ('$kode_escaped', '$nama_escaped', '$deskripsi_escaped')";

        if (mysqli_query($conn, $query)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan ke database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Departemen - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include_once '../sidebar.php'; ?>

    <!-- Header -->
    <header class="fixed top-0 left-64 right-0 z-10 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">Tambah Departemen</h1>
        </div>
    </header>

    <!-- Main content -->
    <main class="pt-20 pl-72 pr-6 pb-10">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
                <div>
                    <label for="kode_departemen" class="block text-sm font-medium text-gray-700">Kode Departemen</label>
                    <input type="text" name="kode_departemen" id="kode_departemen" required
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="nama_departemen" class="block text-sm font-medium text-gray-700">Nama Departemen</label>
                    <input type="text" name="nama_departemen" id="nama_departemen" required
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label for="deskripsi_departemen" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi_departemen" id="deskripsi_departemen" rows="3"
                        class="mt-1 block w-full bg-white border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="index.php"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

>>>>>>> e6964740e0c6cf9ccfd74809dbf2030d578a4d3b
