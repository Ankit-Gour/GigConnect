<?php
// Connect to your database
$conn = new mysqli('localhost', 'root', '1913', 'gigconnect'); // Update with your credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all unique skills
$sql = "SELECT DISTINCT skills FROM gig_workers";
$result = $conn->query($sql);

$skills = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = htmlspecialchars($row['skills']);
    }
}

// Close database connection
$conn->close();
?>
<html lang="en"><head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigConnect - Find Your Fit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensure content doesn't overflow */
        }
        .skills-container {
            text-align: center;
        }
        .skills-container h1 {
            font-size: 2.5rem;
            color: #0056b3; /* Blue color for the heading */
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-in-out;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .search-box input:focus {
            border-color: #0056b3; /* Blue border on focus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            outline: none;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 0;
            list-style-type: none;
            margin: 0;
        }
        .skill-item {
            background-color: #007bff; /* Blue background for skill items */
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 1rem;
            text-align: center;
            text-decoration: none; /* Remove underline from links */
            max-width: 220px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.5s ease-in-out;
        }
        .skill-item:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            color: #e0e0e0;
        }
        .skills-container p {
            font-size: 1.125rem;
            color: #777;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-box input {
                max-width: 100%;
            }
            .skills-list {
                flex-direction: column;
                align-items: center;
            }
            .skill-item {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Animations */
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

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
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



    <div class="container">
        <div class="skills-container">
            <h1>Available Skills</h1>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search for skills...">
            </div>
            <div id="skillsList" class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <a href="fetch_workers.php?skill=<?php echo urlencode($skill); ?>" class="skill-item"><?php echo $skill; ?></a>
                <?php endforeach; ?>
            </div>
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
    

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            var input = this.value.toLowerCase();
            var skillItems = document.querySelectorAll('.skill-item');

            skillItems.forEach(function(item) {
                var skill = item.textContent.toLowerCase();
                if (skill.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
    <script src="index.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
