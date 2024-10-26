<?php
// login.php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "1913";
$dbname = "gigconnect";

// $servername = "127.0.0.1"; // 
// $username = "u874458985_root"; 
// $password = "GigConnect@1913"; 
// $dbname = "u874458985_gigconnect"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT id FROM gig_workers WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);

// Execute the statement
$stmt->execute();

// Store result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the worker ID
    $row = $result->fetch_assoc();
    $worker_id = $row['id'];
    
    // Redirect to dashboard.php with worker ID
    header("Location: dashboard.php?id=" . urlencode($worker_id));
    exit();
} else {
    // If record not found, show error message
    echo "<p>Invalid email or password.</p>";
}

$stmt->close();
$conn->close();
?>
