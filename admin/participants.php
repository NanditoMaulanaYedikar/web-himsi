<?php
session_start();

// Check if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$root_path = dirname(__DIR__);
include $root_path . '/config/db.php';

// Get all participants with event information
$query = "SELECT p.*, e.nama as event_nama, e.tanggal as event_tanggal 
          FROM peserta p 
          JOIN event e ON p.event_id = e.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

// Get all events for the filter
$events_query = "SELECT id, nama FROM event ORDER BY nama";
$events_result = mysqli_query($conn, $events_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peserta - HIMSI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <div class="flex">
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <main class="flex-1 ml-64 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Kelola Peserta</h1>
                        <div class="flex gap-4">
                            <select id="eventFilter" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Event</option>
                                <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                                    <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['nama']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <button onclick="exportToCSV()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-download mr-2"></i>Export CSV
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Participants Table -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="participantsTable">
                                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                    <?php while ($participant = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50" data-event-id="<?php echo $participant['event_id']; ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($participant['event_nama']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($participant['event_tanggal'])); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php 
                                                $data = json_decode($participant['data_peserta'], true);
                                                foreach ($data as $key => $value) {
                                                    echo '<div class="text-sm text-gray-900">';
                                                    echo '<span class="font-medium">' . htmlspecialchars($key) . ':</span> ';
                                                    echo htmlspecialchars($value);
                                                    echo '</div>';
                                                }
                                                ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('d M Y H:i', strtotime($participant['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="viewDetails(<?php echo $participant['id']; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <button onclick="deleteParticipant(<?php echo $participant['id']; ?>)" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada peserta yang terdaftar
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Peserta</h3>
                <div id="participantDetails" class="space-y-2"></div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Event filter functionality
        document.getElementById('eventFilter').addEventListener('change', function() {
            const eventId = this.value;
            const rows = document.querySelectorAll('#participantsTable tr[data-event-id]');
            
            rows.forEach(row => {
                if (!eventId || row.dataset.eventId === eventId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // View participant details
        function viewDetails(id) {
            const modal = document.getElementById('detailsModal');
            const detailsContainer = document.getElementById('participantDetails');
            
            // Fetch participant details
            fetch(`get_participant.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    for (const [key, value] of Object.entries(data)) {
                        html += `
                            <div class="border-b pb-2">
                                <div class="font-medium text-gray-700">${key}</div>
                                <div class="text-gray-600">${value}</div>
                            </div>
                        `;
                    }
                    detailsContainer.innerHTML = html;
                    modal.classList.remove('hidden');
                });
        }

        // Close modal
        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        // Delete participant
        function deleteParticipant(id) {
            if (confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
                fetch(`delete_participant.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus peserta: ' + data.message);
                    }
                });
            }
        }

        // Export to CSV
        function exportToCSV() {
            const eventId = document.getElementById('eventFilter').value;
            window.location.href = `export_participants.php${eventId ? '?event_id=' + eventId : ''}`;
        }
    </script>
</body>
</html> 