<?php
// Connect to your database
$conn = new mysqli('localhost', 'root', '1913', 'gigconnect'); // Update with your credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the skill from the query parameter
$skill = isset($_GET['skill']) ? trim($_GET['skill']) : '';

// Escape skill to prevent SQL injection
$skill = $conn->real_escape_string($skill);

// Query to search for workers with the given skill
$sql = "SELECT * FROM gig_workers WHERE skills LIKE '%$skill%'";
$result = $conn->query($sql);

// Check if query execution was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Start output buffer to handle results
ob_start();

echo '<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div id="logo">
                <a href="index.html">
                    <img src="images/logo.png" alt="GigConnect" class="logo-img">
                </a>
            </div>
            <button class="navbar-toggler" id="hammer" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <!-- Home Link -->
                    <li class="nav-item">
                        <a class="nav-link animated-link" aria-current="page" href="index.html">Home</a>
                    </li>
    
    
                    <!-- Contact Us Link -->
                    <li class="nav-item">
                        <a class="nav-link animated-link" href="#contact">Contact us</a>
                    </li>
    
                    <!-- Find Workers Link -->
                    <li class="nav-item">
                        <a class="nav-link animated-link" href="fetch_worker.php">Find Workers</a>
                    </li>
    
                    <!-- Gig Login Button -->
                    <li class="nav-item">
                        <a class="nav-link animated-link" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="toggle()" id="giglogin">Gig Worker Login</a>
                    </li>
    
                    <!-- Worker Registration Button -->
                    <li class="nav-item">
                        <a class="nav-link  animated-link" id="gig" data-bs-toggle="modal" data-bs-target="#exampleModal1" onclick="toggle()">Worker Registration</a>
                    </li>
    
                    <!-- Industry Registration Link -->
                    <li class="nav-item">
                        <a class="nav-link animated-link" href="industry_registration.html" id="industry">Industry Registration</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    

<!-- Login Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content modal-arrival-animation">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="login">
                    <div class="form-container">
                        <form action="login.php" method="post">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>

                            <input type="submit" value="Login" class="btn btn-primary">
                        </form>
                        <div class="forgot-password">
                            <button type="button" class="btn btn-link forgot-password-btn" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</button>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary custom-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-arrival-animation">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="forgotPasswordLabel">Forgot Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="forgot_password.php" method="post">
                    <label for="forgot-email">Email:</label>
                    <input type="email" id="forgot-email" name="email" class="form-control" required>

                    <label for="forgot-phone">Mobile Number:</label>
                    <input type="text" id="forgot-phone" name="phone" class="form-control" required>

                    <input type="submit" value="Reset Password" class="btn btn-primary">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary custom-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-arrival-animation">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Gig Worker Registration</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="registration">
                    <div class="form-container">
                        <p>Please fill out the form below to register as a gig worker.</p>
                        <form action="register.php" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone:</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="pincode" class="form-label">Pincode:</label>
                                <input type="text" id="pincode" name="pincode" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="work-preference" class="form-label">Work Preference:</label>
                                <input type="text" id="work-preference" name="work_preference" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="skills" class="form-label">Current Skills:</label>
                                <textarea id="skills" name="skills" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




';
echo '<div class="workers-container">';
echo '<h1>Workers with Skill: ' . htmlspecialchars($skill) . '</h1>';

if ($result->num_rows > 0) {
    echo '<div class="workers-list">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="worker-item">';
        echo '<h2>Worker ID: ' . htmlspecialchars($row['id']) . '</h2>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['name']) . '</p>';
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skills']) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
        echo '<p><strong>Contact:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<p><strong>About:</strong> ' . htmlspecialchars($row['work_preference']) . '</p>';
        echo '<div class="action-buttons">';
        // Email button
        echo '<a href="mailto:' . htmlspecialchars($row['email']) . '" class="btn contact-btn">Email Worker</a>';
        // Phone button
        echo '<a href="tel:' . htmlspecialchars($row['phone']) . '" class="btn contact-btn">Call Worker</a>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p class="no-results">No workers found with the skill "' . htmlspecialchars($skill) . '".</p>';
}

// Get and clean output buffer content
$output = ob_get_clean();

// Close database connection
$conn->close();

// Output the result
echo $output;
?>
<html lang="en"><head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <title>Workers with Skill</title>
    <style>
    
        .workers-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff; /* White background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            font-size: 28px;
            color: #003366; /* Dark blue text */
            text-align: center;
            margin-bottom: 30px;
        }
        .workers-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .worker-item {
            flex: 1 1 calc(33.333% - 20px);
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out; /* Animation for entry */
        }
        .worker-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .worker-item h2 {
            font-size: 22px;
            color: #003366; /* Dark blue for worker ID */
            margin-bottom: 15px;
        }
        .worker-item p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }
        .action-buttons {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF; /* Blue button */
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0 5px;
        }
        .btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-3px);
        }
        .contact-btn {
            background-color: #28a745; /* Green button */
        }
        .contact-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
        /* Media Queries */
        @media (max-width: 768px) {
            .worker-item {
                flex: 1 1 calc(50% - 20px);
            }
        }
        @media (max-width: 480px) {
            .worker-item {
                flex: 1 1 100%;
            }
        }
        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>










   

 <!-- Footer -->
 <footer>
        <div class="footer-content" >
            <div id="logo" class="footer-logo" ><img src="images/logo.png" alt="GigConnect"></div>
            <ul>
                <li><a href="https://www.freejobalert.com/mp-government-jobs/" target="_blank">Jobs Information</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="mailto:ankitgour19168893@gmail.com">Contact Us</a></li> <!-- Email Link -->

                <li><a href="tel:+919399484597">+91 9399484597</a></li> <!-- Phone Number -->
            </ul>
            <div class="footer-social">
                <a href="https://www.linkedin.com/company/gigconnect-findyourfit" target="_blank"><img src="images/linkedin.png"></a>
            </div>
            <p >© 2024 GigConnect. All rights reserved.</p>
        </div>
       <div id="contact"></div>


       <div id="google_translate_element"></div>

<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
  }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    </footer>
    
 
<script src="index.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body></html>