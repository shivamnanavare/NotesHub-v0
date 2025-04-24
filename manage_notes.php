<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    echo "Access Denied!";
    exit;
}

$sql = "SELECT f.id, f.filename, u.username FROM notes f 
        JOIN users u ON f.user_id = u.id"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Notes</title>
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>
    <h2>Manage Student Notes</h2>
    <table border="1">
        <tr>
            <th>File Name</th>
            <th>Uploaded By</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['filename']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <a href="delete_note.php?id=<?php echo $row['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this note?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>