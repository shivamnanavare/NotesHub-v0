<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die("No file uploaded or upload error.");
}

if (!isset($_POST['yearUpload']) || !isset($_POST['subjectUpload'])) {
    die("Year or subject missing.");
}

$user_id = $_SESSION['user_id'];
$year = $_POST['yearUpload'];
$subject = $_POST['subjectUpload'];
$filename = $_FILES['file']['name'];
$file_tmp = $_FILES['file']['tmp_name'];
$destination = "uploads/" . basename($filename);
if (file_exists($destination)) {
    die("Error: This file already exists. Please upload a different file.");
}
$stmt = $conn->prepare("SELECT id FROM notes WHERE filename = ?");
$stmt->bind_param("s", $filename);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    die("Error: This file has already been uploaded.");
}
if (move_uploaded_file($file_tmp, $destination)) {
    $query = "INSERT INTO notes (year, subject, filename, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $year, $subject, $filename, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php?upload_success=1");
        exit();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Error moving file.";
}
?>
