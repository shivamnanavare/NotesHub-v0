<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $role = $_POST['role']; 
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); 
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "Username or email already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $passwordHash, $role); 
            $stmt->execute();
            echo "Account created successfully. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <h2>Sign Up</h2>
    <form method="POST">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <label for="confirmPassword">Confirm Password:</label><br>
        <input type="password" name="confirmPassword" required><br><br>
        
        <label for="role">Select Role:</label><br>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br><br>
        
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
