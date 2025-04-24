<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    echo "Access Denied!";
    exit;
}

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];

    // Get the file path before deleting
    $sql = "SELECT filename FROM notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $note_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        $file_path = "uploads/" . $file['filename']; // Adjust upload path
        
        // Delete file from server
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete from database
        $delete_sql = "DELETE FROM notes WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $note_id);
        $delete_stmt->execute();

        echo "File deleted successfully!";
        header("Location: manage_notes.php");
        exit;
    } else {
        echo "File not found!";
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>