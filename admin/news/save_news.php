<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$root_path = dirname(dirname(dirname(__FILE__)));
include $root_path . '/config/db.php';

// Create news directory if it doesn't exist
$news_dir = $root_path . '/asset/news';
if (!file_exists($news_dir)) {
    mkdir($news_dir, 0777, true);
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = $_POST['title'];
$author = $_POST['author'];
$content = $_POST['content'];
$status = $_POST['status'];

// Handle image upload
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target = $news_dir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $image = $filename;
    }
}

if ($id) {
    // Update existing news
    if ($image) {
        // Delete old image if exists
        $query = "SELECT image FROM news WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $old_news = mysqli_fetch_assoc($result);
        
        if ($old_news['image']) {
            $old_image = $news_dir . '/' . $old_news['image'];
            if (file_exists($old_image)) {
                unlink($old_image);
            }
        }

        $query = "UPDATE news SET title = ?, author = ?, content = ?, status = ?, image = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $author, $content, $status, $image, $id);
    } else {
        $query = "UPDATE news SET title = ?, author = ?, content = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $author, $content, $status, $id);
    }
} else {
    // Insert new news
    $query = "INSERT INTO news (title, author, content, status, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $title, $author, $content, $status, $image);
}

if (mysqli_stmt_execute($stmt)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan berita']);
} 