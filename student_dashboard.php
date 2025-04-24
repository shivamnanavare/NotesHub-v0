<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/student_dashboard.css">

</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?> (Student)</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
    
    <h2>Your Notes</h2>
    <table border="1">
    <tr>
        <th>Title</th>
        <th>Subject</th>
        <th>Year</th>
        <th>Download</th>
        <th>Action</th>
    </tr>
    <?php
    $stmt = $conn->prepare("SELECT id, filename, subject, year FROM notes WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['filename']) . "</td>";
        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
        echo "<td>" . htmlspecialchars($row['year']) . "</td>";
        echo "<td><a href='uploads/" . urlencode($row['filename']) . "' download>Download</a></td>";
        echo "<td><a href='delete_note.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
