<<<<<<< HEAD
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

$kode = $_GET['kode'] ?? '';
if (!$kode) {
    header("Location: index.php");
    exit();
}

$errors = [];
$query = mysqli_query($conn, "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");
$departemen = mysqli_fetch_assoc($query);

if (!$departemen) {
    echo "Departemen tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_departemen = trim($_POST['nama_departemen'] ?? '');
    $deskripsi_departemen = trim($_POST['deskripsi_departemen'] ?? '');

    if (empty($nama_departemen)) {
        $errors[] = "Nama departemen harus diisi.";
    }

    if (empty($errors)) {
        $nama_departemen_escaped = mysqli_real_escape_string($conn, $nama_departemen);
        $deskripsi_departemen_escaped = mysqli_real_escape_string($conn, $deskripsi_departemen);

        $update = mysqli_query($conn, "UPDATE departemen SET 
            nama_departemen = '$nama_departemen_escaped', 
            deskripsi_departemen = '$deskripsi_departemen_escaped'
            WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");

        if ($update) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Departemen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: "Inter", sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include_once '../sidebar.php'; ?>
    <header class="bg-white shadow-md ml-64">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <h1 class="text-xl font-semibold text-gray-800">Edit Departemen</h1>
        </div>
    </header>
    <main class="flex-grow max-w-3xl mx-auto p-4 ml-64 bg-white rounded shadow">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <div>
                <label for="nama_departemen" class="block text-sm font-medium text-gray-700">Nama Departemen</label>
                <input type="text" name="nama_departemen" id="nama_departemen" value="<?= htmlspecialchars($departemen['nama_departemen']) ?>" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
            </div>
            <div>
                <label for="deskripsi_departemen" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi_departemen" id="deskripsi_departemen" rows="4" class="mt-1 block w-full rounded border-gray-300 shadow-sm"><?= htmlspecialchars($departemen['deskripsi_departemen']) ?></textarea>
            </div>
            <div class="flex justify-end">
                <a href="index.php" class="mr-4 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Perbarui</button>
            </div>
        </form>
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

$kode = $_GET['kode'] ?? '';
if (!$kode) {
    header("Location: index.php");
    exit();
}

$errors = [];
$query = mysqli_query($conn, "SELECT * FROM departemen WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");
$departemen = mysqli_fetch_assoc($query);

if (!$departemen) {
    echo "Departemen tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_departemen = trim($_POST['nama_departemen'] ?? '');
    $deskripsi_departemen = trim($_POST['deskripsi_departemen'] ?? '');

    if (empty($nama_departemen)) {
        $errors[] = "Nama departemen harus diisi.";
    }

    if (empty($errors)) {
        $nama_departemen_escaped = mysqli_real_escape_string($conn, $nama_departemen);
        $deskripsi_departemen_escaped = mysqli_real_escape_string($conn, $deskripsi_departemen);

        $update = mysqli_query($conn, "UPDATE departemen SET 
            nama_departemen = '$nama_departemen_escaped', 
            deskripsi_departemen = '$deskripsi_departemen_escaped'
            WHERE kode_departemen = '" . mysqli_real_escape_string($conn, $kode) . "'");

        if ($update) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Departemen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: "Inter", sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include_once '../sidebar.php'; ?>
    <header class="bg-white shadow-md ml-64">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <h1 class="text-xl font-semibold text-gray-800">Edit Departemen</h1>
        </div>
    </header>
    <main class="flex-grow max-w-3xl mx-auto p-4 ml-64 bg-white rounded shadow">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <div>
                <label for="nama_departemen" class="block text-sm font-medium text-gray-700">Nama Departemen</label>
                <input type="text" name="nama_departemen" id="nama_departemen" value="<?= htmlspecialchars($departemen['nama_departemen']) ?>" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
            </div>
            <div>
                <label for="deskripsi_departemen" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi_departemen" id="deskripsi_departemen" rows="4" class="mt-1 block w-full rounded border-gray-300 shadow-sm"><?= htmlspecialchars($departemen['deskripsi_departemen']) ?></textarea>
            </div>
            <div class="flex justify-end">
                <a href="index.php" class="mr-4 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Perbarui</button>
            </div>
        </form>
    </main>
</body>
</html>
>>>>>>> e6964740e0c6cf9ccfd74809dbf2030d578a4d3b
