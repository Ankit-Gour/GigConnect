<?php
// Database connection settings
$host = 'localhost';
$db = 'gigconnect'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = '1913'; // Replace with your database password

// Establish a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all records from the 'industry' table
$sql = "SELECT industry_name, location, domain, work_type, skills FROM industry";
$result = $conn->query($sql);

// Initialize an array to store industries data
$industries = [];

if ($result->num_rows > 0) {
    // Fetch and store each row into the industries array
    while ($row = $result->fetch_assoc()) {
        $industries[] = [
            'name' => $row['industry_name'],
            'location' => $row['location'],
            'domain' => $row['domain'],
            'work_type' => $row['work_type'],
            'skills' => $row['skills']
        ];
    }
}

// Close the connection
$conn->close();

// Return the data as a JSON object
header('Content-Type: application/json');
echo json_encode($industries);
