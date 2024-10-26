<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "1913";
$dbname = "gigconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch worker ID from URL query parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $worker_id = intval($_GET['id']); // Sanitize input
} else {
    die("Worker ID is missing.");
}

// Prepare and execute the SQL query to fetch worker details
$sql = "SELECT * FROM gig_workers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Prepare and execute the SQL query to fetch industries demanding similar skills
$skills = $user['skills'];
$industry_sql = "SELECT industry_name, location, contact_email, contact_phone FROM industry WHERE skills LIKE ?";
$like_param = '%' . $skills . '%';
$industry_stmt = $conn->prepare($industry_sql);
$industry_stmt->bind_param("s", $like_param);
$industry_stmt->execute();
$industries_result = $industry_stmt->get_result();

// Close connection
$stmt->close();
$industry_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigConnect - Gig Worker Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
    /* CSS for the dashboard */
    .dashboard-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dashboard-container h2 {
        text-align: center;
        font-size: 2rem;
        color: #003366;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #003366;
        padding-bottom: 0.5rem;
        animation: slideIn 1.5s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(0);
        }
    }

    .dashboard-details, .industries-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .detail-item, .industry-item {
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .detail-item:hover, .industry-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .detail-item span, .industry-item span {
        font-weight: bold;
        color: #003366;
    }

    .industry-item:nth-child(even) {
        background-color: #f1f1f1;
    }

    /* Animations for industry items */
    .industry-item {
        opacity: 0;
        animation: fadeInUp 1.2s forwards;
        animation-delay: calc(0.2s * var(--index));
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Footer styles */
    footer {
        background-color: #003366;
        color: white;
        padding: 1.5rem 0;
        text-align: center;
    }

    .footer-content {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 1rem;
        animation: fadeIn 2s ease-in-out;
    }

    .footer-content h4 {
        margin-bottom: 1rem;
    }

    .footer-content ul {
        list-style-type: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .footer-content ul li {
        display: inline;
        margin: 0 1rem;
    }

    .footer-content a {
        color: #66ccff;
        text-decoration: none;
    }

    .footer-content a:hover {
        text-decoration: underline;
    }

    .contact-btn {
        display: inline-block;
        margin: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #66ccff;
        color: #003366;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .contact-btn:hover {
        background-color: #3399ff;
    }

    /* Button animation */
    .contact-btn {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .footer-content ul {
            margin-bottom: 1rem;
        }

        .footer-content ul li {
            display: block;
            margin: 0.5rem 0;
        }

        .contact-btn {
            display: block;
            margin: 0.5rem auto;
        }
    }

    .dashboard-details, .industries-list {
	display: flex;
	flex-direction: column;
	gap: 1rem;
	margin-bottom: 23px;
}
</style>

</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
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





<!-- Dashboard -->
<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <div class="dashboard-details">
        <div class="detail-item"><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></div>
        <div class="detail-item"><span>Phone:</span> <?php echo htmlspecialchars($user['phone']); ?></div>
        <div class="detail-item"><span>Location:</span> <?php echo htmlspecialchars($user['location']); ?></div>
        <div class="detail-item"><span>Work Preference:</span> <?php echo htmlspecialchars($user['work_preference']); ?></div>
        <div class="detail-item"><span>Skills:</span> <?php echo nl2br(htmlspecialchars($user['skills'])); ?></div>
    </div>

    <h3>Industries Demanding Your Skills:</h3>
    <div class="industries-list">
        <?php if ($industries_result->num_rows > 0) {
            while ($industry = $industries_result->fetch_assoc()) { ?>
                <div class="industry-item">
                    <span>Industry Name:</span> <?php echo htmlspecialchars($industry['industry_name']); ?><br>
                    <span>Location:</span> <?php echo htmlspecialchars($industry['location']); ?><br>
                    <span>Contact Email:</span> <a href="mailto:<?php echo htmlspecialchars($industry['contact_email']); ?>"><?php echo htmlspecialchars($industry['contact_email']); ?></a><br>
                    <span>Contact Phone:</span> 
                    <?php if (!empty($industry['contact_phone'])): ?>
                        <a href="tel:<?php echo htmlspecialchars($industry['contact_phone']); ?>"><?php echo htmlspecialchars($industry['contact_phone']); ?></a>
                    <?php else: ?>
                        Not provided
                    <?php endif; ?>
                </div>
        <?php } } else { ?>
            <div class="industry-item">
                No industries currently demanding your skills.
            </div>
        <?php } ?>
    </div>
</div>

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
    


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
