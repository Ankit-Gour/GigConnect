<?php
// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = "1913"; // Replace with your database password
$dbname = "gigconnect"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$pincode = $_POST['pincode']; // Assuming you have a pincode field
$work_preference = $_POST['work_preference'];
$skills = $_POST['skills'];
$password = $_POST['password']; 

// Fetch location data from the API
$apiUrl = "https://api.postalpincode.in/pincode/" . $pincode;
$response = file_get_contents($apiUrl);
$responseData = json_decode($response, true);

// Check if the response is valid and has data
if (isset($responseData[0]['PostOffice'][0])) {
    $postOffice = $responseData[0]['PostOffice'][0];
    $location = $postOffice['District'] . ", " . $postOffice['State'];
} else {
    $location = "Unknown"; // Handle unknown pincode case
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO gig_workers (name, email, phone, location, work_preference, skills, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $email, $phone, $location, $work_preference, $skills, $password);

// Execute the statement and display a success message with styles and auto-redirect
if ($stmt->execute()) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Registration Successful</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .success-message {
                background-color: #5cb85c;
                color: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                font-size: 24px;
                width: 80%;
                max-width: 600px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                animation: fadeIn 1s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
            .success-message a {
                color: #fff;
                font-weight: bold;
                text-decoration: none;
            }
            .success-message a:hover {
                text-decoration: underline;
            }
        </style>
        <meta http-equiv='refresh' content='5;url=index.html'>
    </head>
    <body>
        <div class='success-message'>
            Registration successful!<br>
            You will be redirected to home page shortly.<br>
            Please Login from there 
        </div>
    </body>
    </html>";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
