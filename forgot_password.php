<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin: 20px 0;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 1.2rem;
            }

            input[type="submit"] {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset</h2>
        <?php
        // Include database connection
        $host = 'localhost';
        $db_user = 'root';
        $db_password = '1913';
        $db_name = 'gigconnect';

        // Create connection using MySQLi
        $conn = new mysqli($host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            // Check if the user exists in the GigConnect database
            $query = "SELECT * FROM gig_workers WHERE email = ? AND phone = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If user exists, allow them to reset the password
                echo '<form action="reset_password.php" method="post">
                        <input type="hidden" name="email" value="' . $email . '">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <input type="submit" value="Change Password">
                      </form>';
            } else {
                echo '<div class="message">No account found with the provided email and phone number.</div>';
            }
        }
        ?>
    </div>
</body>
</html>
