<?php
// Backup existing file before replacing
copy(__FILE__, __DIR__ . '/registrations_backup.php');

// Fix relative path to config
$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

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

// Get event form fields with types
$fields_query = mysqli_query($conn, "SELECT nama_field, tipe_field FROM event_form_field WHERE event_id = '$event_id' ORDER BY id ASC");
$fields = [];
while ($field = mysqli_fetch_assoc($fields_query)) {
    $fields[] = ['name' => $field['nama_field'], 'type' => $field['tipe_field']];
}

function safe_htmlspecialchars($value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    if (!is_string($value)) {
        $value = json_encode($value);
    }
    return htmlspecialchars($value);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi Peserta untuk Event: <?php echo htmlspecialchars($event['nama']); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #003366;
      min-height: 100vh;
      padding: 2rem;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      color: white;
    }
    .container {
      background: #003366;
      border-radius: 0;
      max-width: 700px;
      width: 100%;
      padding: 2.5rem 2rem 3rem;
    }
    .btn-export {
      background: #ffac00;
      color: #003366;
      font-weight: 700;
      border-radius: 9999px;
      padding: 0.5rem 1.5rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-family: 'Poppins', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      text-decoration: none;
    }
    .btn-export:hover {
      background: #e69a00;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      color: white;
    }
    thead tr {
      border-bottom: 2px solid #ffac00;
    }
    thead th {
      padding: 0.75rem 1rem;
      font-weight: 700;
      font-size: 1rem;
      text-align: left;
      color: white;
    }
    tbody tr {
      border-bottom: 1px solid #1e3a8a;
      transition: background-color 0.3s ease;
    }
    tbody tr:hover {
      background-color: #002244;
    }
    tbody td {
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      color: white;
    }
    h1 span {
      color: #ffac00;
      font-weight: 700;
    }
  </style>
</head>
<body>
  <main class="container">
    <h1 class="text-3xl font-extrabold mb-6 text-center">
      Registrasi Peserta untuk Event: <span><?php echo htmlspecialchars($event['nama']); ?></span>
    </h1>
    <div class="flex justify-end mb-6">
      <a href="export_csv.php?event_id=<?php echo $event_id; ?>" class="btn-export" title="Export to CSV">
        <span class="text-xl">📥</span>
        Export to CSV
      </a>
    </div>
    <div class="overflow-x-auto rounded-md">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <?php foreach ($fields as $field): ?>
              <th><?php echo htmlspecialchars($field['name']); ?></th>
            <?php endforeach; ?>
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
              foreach ($fields as $field) {
                  $field_name = $field['name'];
                  $field_type = $field['type'];
                  $value = $data[$field_name] ?? '-';
                  if ($field_type === 'file' && $value !== '-') {
                      $value_str = is_string($value) ? $value : json_encode($value);
                      $file_url_show = "/himsi/asset/uploads/" . safe_htmlspecialchars($value_str);
                      $file_url_download = "/himsi/admin/event/download.php?file=" . urlencode($value_str);
                      echo "<td>
                              <a href='" . $file_url_show . "' target='_blank'><button type='button'>Show</button></a>
                              <a href='" . $file_url_download . "' target='_blank' style='margin-left: 5px;'><button type='button'>Download</button></a>
                            </td>";
                  } else {
                      echo "<td>" . safe_htmlspecialchars($value) . "</td>";
                  }
              }
              echo "<td>" . date('d F Y H:i', strtotime($row['created_at'])) . "</td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
