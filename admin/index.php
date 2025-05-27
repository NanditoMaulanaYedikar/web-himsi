<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

// Get statistics
$stats = [
    'messages' => 0,
    'participants' => 0,
    'events' => 0,
    'gallery' => 0
];

// Get message count
$query = "SELECT COUNT(*) as count FROM contact_messages";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $stats['messages'] = $row['count'];
}

// Get participant count
$query = "SELECT COUNT(*) as count FROM peserta";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $stats['participants'] = $row['count'];
}

// Get event count
$query = "SELECT COUNT(*) as count FROM event";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $stats['events'] = $row['count'];
}

// Get gallery count
$query = "SELECT COUNT(*) as count FROM galeri_foto";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $stats['gallery'] = $row['count'];
}

// Get recent participants
$recent_participants = [];
$query = "SELECT p.*, e.nama as event_name 
          FROM peserta p 
          JOIN event e ON p.event_id = e.id 
          ORDER BY p.created_at DESC LIMIT 5";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $recent_participants[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HIMSI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <div class="flex">
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <main class="flex-1 ml-64 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Section -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Panel Admin HIMSI</h1>
                        <p class="text-gray-600 text-lg mb-8">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Messages Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <i class="fas fa-envelope text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Pesan</p>
                                <p class="text-2xl font-semibold text-gray-800"><?php echo $stats['messages']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Participants Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Peserta</p>
                                <p class="text-2xl font-semibold text-gray-800"><?php echo $stats['participants']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Events Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Event</p>
                                <p class="text-2xl font-semibold text-gray-800"><?php echo $stats['events']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                <i class="fas fa-images text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Foto</p>
                                <p class="text-2xl font-semibold text-gray-800"><?php echo $stats['gallery']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Participants -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Peserta Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recent_participants as $participant): 
                                    $data = json_decode($participant['data_peserta'], true);
                                    $name = isset($data['Nama']) ? $data['Nama'] : 'N/A';
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($participant['event_name']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($name); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('d/m/Y', strtotime($participant['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="participants.php" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Lihat Semua Peserta →</a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="contact/" class="flex items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-envelope text-blue-500 mr-3"></i>
                        <span class="text-gray-700">Kelola Pesan</span>
                    </a>
                    <a href="participants.php" class="flex items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-users text-green-500 mr-3"></i>
                        <span class="text-gray-700">Kelola Peserta</span>
                    </a>
                    <a href="event/" class="flex items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-calendar-alt text-purple-500 mr-3"></i>
                        <span class="text-gray-700">Kelola Event</span>
                    </a>
                    <a href="gallery/" class="flex items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                        <i class="fas fa-images text-yellow-500 mr-3"></i>
                        <span class="text-gray-700">Kelola Galeri</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 