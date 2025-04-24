<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    $year = $_POST['yearUpload'];
    $subject = $_POST['subjectUpload'];
    $user_id = $_SESSION['user_id']; 
    
    if ($_FILES['fileUpload']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["fileUpload"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $targetFilePath)) {
            $stmt = $conn->prepare("INSERT INTO notes (year, subject, filename, user_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $year, $subject, $fileName, $user_id);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Notes Sharing Platform</title>
    <link rel="stylesheet" href="css/download.css">
</head>
<body>
    <header>
        <img src="img/iconF.png" placeholder="Icon" style="width: 250px;">
        <nav>
            <ul>
                <li><a href="#download" onclick="showSection('download')" class="anchor">Download</a></li>
                <li><a href="#upload" onclick="showSection('upload')" class="anchor">Upload</a></li>
                <li><a href="#topcontributers" onclick="showSection('topcontributers')" class="anchor">Top contributer</a></li>
                <li><a href="logout.php" class="anchor">Logout</a></li>
            </ul>
        </nav>
    </header>
    <?php
if (isset($_GET['upload_success'])) {
    echo "<p >File uploaded successfully!🥳</p>";
}
?>

    <div id="download" class="section">
        <h2>Download Notes</h2>
        <form id="downloadForm">
            <label for="year">Select Year:</label>
            <select id="year">
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
            </select>

            <label for="subject">Select Subject:</label>
            <select id="subject" name="subject">
            <optgroup label="1st Year">
        <option value="Communication skill">Communication skill</option>
        <option value="fundamental of computer">Fundamental of Computer</option>
        <option value="Basics of Operating System">Basics of Operating System</option>
        <option value="Programming using C">Programming using C</option>
        <option value="Python-I">Python-I</option>
        <option value="Basic Electronics Paper–I">Basic Electronics Paper–I</option>
        <option value="Advanced Electronics Paper–I">Advanced Electronics Paper–I</option>
        <option value="Numerical Methods">Numerical Methods</option>
        <option value="Graph Theory">Graph Theory</option>
    </optgroup>

    <optgroup label="2nd Year">
        <option value="Data Structure using C++-I">Data Structure using C++-I</option>
        <option value="Linux OS and Shell Scripting">Linux OS and Shell Scripting</option>
        <option value="Software Engineering">Software Engineering</option>
        <option value="Database Management System-I">Database Management System-I</option>
        <option value="probability Theory">Probability Theory</option>
        <option value="Data Science with Python">Data Science with Python</option>
        <option value="Web Development using PHP">Web Development using PHP</option>
    </optgroup>
</select>


            <button type="button" onclick="getNotes()">Get Notes</button>
        </form>
        <div id="notesList"></div>
    </div>

    <div id="upload" class="section" style="display:none;">
        <h2>Upload Notes</h2>

        <form action="upload.php" method="POST" enctype="multipart/form-data">

            <label for="yearUpload">Select Year:</label>
            <select name="yearUpload">
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
            </select>

            <label for="subjectUpload">Select Subject:</label>
            <select name="subjectUpload">
    <optgroup label="1st Year">
        <option value="Communication skill">Communication skill</option>
        <option value="fundamental of computer">Fundamental of Computer</option>
        <option value="Basics of Operating System">Basics of Operating System</option>
        <option value="Programming using C">Programming using C</option>
        <option value="Python-I">Python-I</option>
        <option value="Basic Electronics Paper–I">Basic Electronics Paper–I</option>
        <option value="Advanced Electronics Paper–I">Advanced Electronics Paper–I</option>
        <option value="Numerical Methods">Numerical Methods</option>
        <option value="Graph Theory">Graph Theory</option>
    </optgroup>

    <optgroup label="2nd Year">
        <option value="Data Structure using C++-I">Data Structure using C++-I</option>
        <option value="Linux OS and Shell Scripting">Linux OS and Shell Scripting</option>
        <option value="Software Engineering">Software Engineering</option>
        <option value="Database Management System-I">Database Management System-I</option>
        <option value="probability Theory">Probability Theory</option>
        <option value="Data Science with Python">Data Science with Python</option>
        <option value="Web Development using PHP">Web Development using PHP</option>
    </optgroup>
</select>


            <label for="fileUpload" class="upload-label">tap here to upload a PDF</label>
            <input type="file" id="fileUpload" name="file" accept=".pdf" required>
            <button type="submit">Upload Notes</button>

        </form>
    </div>
    
    <div id="topcontributers" class="section" style="display:none;">
    <?php
    include 'topcontributer.php';
    ?>
</div>

    </div>

    <script src="script.js"></script>

    <footer>
    <div class="footer">
        <div>
            <h5>for the ECS Students, by the ECS students</h5>
            <h4>Designed by NotesHub team | <a href="credits.html">View Credits </a></h4>
            <h5>&copy; 2025 Sanket Bhuite | All Rights Reserved.</h5>
            
        </div>
    </div>
  </footer>
  
</body>
</html>

<script>


function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    document.getElementById(sectionId).style.display = 'block';
}

function getNotes() {
    const year = document.getElementById('year').value;
    const subject = document.getElementById('subject').value;

    fetch(`download.php?year=${year}&subject=${subject}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('notesList').innerHTML = data;
        });
}
</script>
