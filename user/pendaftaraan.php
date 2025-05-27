<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the database connection file exists
if (!file_exists(__DIR__ . '/config/db.php')) {
    die("Database configuration file not found!");
}

include __DIR__ . '/config/db.php';

// Check if event_id exists and is not empty
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    echo "<script>alert('Error: event_id tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$event_id = intval($_GET['event_id']); // sanitize event_id

// Get event details
$event_query = mysqli_query($conn, "SELECT * FROM event WHERE id = '$event_id'");
if (!$event_query || mysqli_num_rows($event_query) === 0) {
    echo "<script>alert('Error: Event tidak ditemukan.'); window.history.back();</script>";
    exit;
}
$event = mysqli_fetch_assoc($event_query);

// Check if event registration is open
if ($event['status'] == 'closed') {
    echo "<script>alert('Maaf, pendaftaran untuk event ini sudah ditutup.'); window.history.back();</script>";
    exit;
}

// Ambil field-form yang sudah ditambahkan untuk event ini
$sql = "SELECT * FROM event_form_field WHERE event_id = '$event_id'";
$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Error query: " . mysqli_error($conn));
}

// Debug information
echo "<!-- Debug Info:<br>";
echo "Event ID: " . $event_id . "<br>";
echo "SQL: " . $sql . "<br>";
echo "Number of fields: " . mysqli_num_rows($query) . "<br>";
echo "-->";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Event</title>
</head>
<body>

<h1><?php echo htmlspecialchars($event['nama']); ?></h1>
<p class="event-description"><?php echo nl2br(htmlspecialchars($event['deskripsi'])); ?></p>
<p>Tanggal: <?php echo date('d F Y', strtotime($event['tanggal'])); ?></p>

<h2>Form Pendaftaran</h2>
<form action="proses_pendaftaran.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <label><?php echo $row['nama_field']; ?>:</label><br>
        <?php if ($row['tipe_field'] == 'text') { ?>
            <input type="text" name="field[<?php echo $row['id']; ?>]" required><br><br>
        <?php } elseif ($row['tipe_field'] == 'email') { ?>
            <input type="email" name="field[<?php echo $row['id']; ?>]" required><br><br>
        <?php } elseif ($row['tipe_field'] == 'number') { ?>
            <input type="number" name="field[<?php echo $row['id']; ?>]" required><br><br>
        <?php } elseif ($row['tipe_field'] == 'file') { ?>
            <input type="file" name="field[<?php echo $row['id']; ?>]" required><br><br>
        <?php } ?>
    <?php } ?>

    <button type="submit">Daftar</button>
</form>

</body>
</html>
