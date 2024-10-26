<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Update Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .message-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            font-size: 1.5rem;
            color: #333;
        }

        p {
            font-size: 1rem;
            margin-top: 10px;
        }

        .success {
            color: green;
        }

        .failure {
            color: red;
        }
    </style>
    <script>
        // Redirect to index.html after 5 seconds
        setTimeout(function(){
            window.location.href = 'index.html';
        }, 5000);
    </script>
</head>
<body>

<div class="message-box">
    <?php
    $host = 'localhost';       // Replace with your database host
    $db_user = 'root';         // Replace with your database username
    $db_password = '1913';     // Replace with your database password
    $db_name = 'gigconnect';   // Replace with your database name

    // Create connection using MySQLi
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Update the password directly (no hashing)
            $query = "UPDATE gig_workers SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $new_password, $email);  // Directly bind the plain text password

            if ($stmt->execute()) {
                echo '<h2 class="success">Password successfully updated!</h2>';
                echo '<p>You will be redirected to the homepage in a moment</p>';
            } else {
                echo '<h2 class="failure">Error updating password. Please try again.</h2>';
                echo '<p>You will be redirected to the homepage in a moment</p>';
            }
        } else {
            echo '<h2 class="failure">Passwords do not match. Please try again.</h2>';
            echo '<p>You will be redirected to the homepage in a moment.</p>';
        }
    }
    ?>
</div>

</body>
</html>
