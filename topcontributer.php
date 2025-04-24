<html>
<head>
<link rel="stylesheet" href="css/topcontributer.css">
</head>
<body>
<?php

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h2>Top 5 Contributors</h2>";


$teacherQuery = "SELECT users.username, COUNT(notes.id) AS total_uploads 
                 FROM users 
                 JOIN notes ON users.id = notes.user_id 
                 WHERE users.role = 'teacher' 
                 GROUP BY users.id 
                 ORDER BY total_uploads DESC 
                 LIMIT 5";

$teacherResult = $conn->query($teacherQuery);

echo "<h3>Top Teachers</h3>";
if ($teacherResult && $teacherResult->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Uploads</th>
            </tr>";

    $rank = 1;
    while ($row = $teacherResult->fetch_assoc()) {
        echo "<tr>
                <td>{$rank}👑</td>
                <td>{$row['username']}</td>
                <td>{$row['total_uploads']}</td>
              </tr>";
        $rank++;
    }
    echo "</table>";
} else {
    echo "<p>No teachers have uploaded notes yet.</p>";
}

$studentQuery = "SELECT users.username, COUNT(notes.id) AS total_uploads 
                 FROM users 
                 JOIN notes ON users.id = notes.user_id 
                 WHERE users.role = 'student' 
                 GROUP BY users.id 
                 ORDER BY total_uploads DESC 
                 LIMIT 5";

$studentResult = $conn->query($studentQuery);

echo "<h3>Top Students</h3>";
if ($studentResult && $studentResult->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Uploads</th>
            </tr>";

    $rank = 1;
    while ($row = $studentResult->fetch_assoc()) {
        echo "<tr>
                <td>{$rank}👑</td>
                <td>{$row['username']}</td>
                <td>{$row['total_uploads']}</td>
              </tr>";
        $rank++;
    }
    echo "</table>";
} else {
    echo "<p>No students have uploaded notes yet.</p>";
}

?>
</body>
</html>