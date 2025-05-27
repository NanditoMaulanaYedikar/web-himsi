<?php
include __DIR__ . '/../../config/db.php';

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    echo "<script>alert('Error: event_id tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$event_id = intval($_GET['event_id']);

// Verify event exists
$event_query = mysqli_query($conn, "SELECT * FROM event WHERE id = '$event_id'");
if (!$event_query || mysqli_num_rows($event_query) === 0) {
    echo "<script>alert('Error: Event tidak ditemukan.'); window.history.back();</script>";
    exit;
}
$event = mysqli_fetch_assoc($event_query);

// Get registrations
$registrations_query = mysqli_query($conn, "SELECT * FROM peserta WHERE event_id = '$event_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Peserta - <?php echo htmlspecialchars($event['nama']); ?></title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; }
        th { background-color: #f2f2f2; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>

<h1>Registrasi Peserta untuk Event: <?php echo htmlspecialchars($event['nama']); ?></h1>

<?php if (mysqli_num_rows($registrations_query) === 0): ?>
    <p>Belum ada peserta yang mendaftar.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NIM</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($registrations_query)) {
                $data = json_decode($row['data_peserta'], true);
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($data['Nama Lengkap'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($data['NIM'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($data['Email'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($data['No. Telepon'] ?? '-') . "</td>";
                echo "<td>" . date('d F Y H:i', strtotime($row['created_at'])) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>

<p><a href="../event/index.php">Kembali ke Daftar Event</a></p>

</body>
</html>
