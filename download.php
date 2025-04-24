<?php
include 'db.php';

$year = $_GET['year'];
$subject = $_GET['subject'];

$stmt = $conn->prepare("SELECT filename FROM notes WHERE year=? AND subject=?");
$stmt->bind_param("ss", $year, $subject);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="notes-container">';
    while ($row = $result->fetch_assoc()) {
        echo '
            <div class="pdf-card">
                <h4>' . htmlspecialchars($row['filename']) . '
                <a href="uploads/' . htmlspecialchars($row['filename']) . '" download class="download-btn">Download</a></h4>
            </div>
        ';
    }
    echo '</div>';
} else {
    echo '<p class="no-notes">No notes available.</p>';
}
?>
