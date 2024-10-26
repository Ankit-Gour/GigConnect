<?php
// Database credentials
$servername = "localhost";  
$username = "root";         
$password = "1913";             
$dbname = "gigconnect";    

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the 'industry' table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS industry (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    industry_name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    work_type VARCHAR(255) NOT NULL,
    skills TEXT NOT NULL,
    contact_email VARCHAR(255) NULL,
    contact_phone VARCHAR(20) NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $industry_name = $conn->real_escape_string($_POST['industry_name']);
    $location = $conn->real_escape_string($_POST['location']);
    $domain = $conn->real_escape_string($_POST['domain']);
    $work_type = $conn->real_escape_string($_POST['work_type']);
    $skills = $conn->real_escape_string($_POST['skills']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $contact_phone = $conn->real_escape_string($_POST['contact_phone']); 

    // Validate email format
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } else {
        // SQL query to insert form data into the 'industry' table
        $sql = "INSERT INTO industry (industry_name, location, domain, work_type, skills, contact_email, contact_phone)
                VALUES ('$industry_name', '$location', '$domain', '$work_type', '$skills', '$contact_email', '$contact_phone')";

      

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New industry registered successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "');</script>";
        }
    }
}

// Fetch all registered industries
$sql = "SELECT * FROM industry";
$result = $conn->query($sql);

?>




<html lang="en"><head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industry registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.png" type="image/x-icon">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #007BFF; /* Blue color */
            margin-bottom: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card-header {
            font-weight: bold;
            color: #007BFF; /* Blue color */
        }
        .card-content {
            margin-top: 10px;
        }
        /* Styling for table rows */
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light grey for even rows */
        }
        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .card {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div id="logo">
                <a href="index.html">
                    <img src="logo.png" alt="GigConnect" class="logo-img">
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


<div class="container">
    <h2>Registered Industries</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-header">Industry ID: <?= $row['id'] ?></div>
                <div class="card-content">
                    <strong>Industry Name:</strong> <?= htmlspecialchars($row['industry_name']) ?><br>
                    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
                    <strong>Domain:</strong> <?= htmlspecialchars($row['domain']) ?><br>
                    <strong>Type of Work:</strong> <?= htmlspecialchars($row['work_type']) ?><br>
                    <strong>Skills:</strong> <?= htmlspecialchars($row['skills']) ?><br>
                    <strong>Contact Email:</strong> <?= htmlspecialchars($row['contact_email']) ?><br>
                    <strong>Contact Phone:</strong> <?= !empty($row['contact_phone']) ? htmlspecialchars($row['contact_phone']) : 'Not provided' ?><br>
                    <strong>Registration Date:</strong> <?= htmlspecialchars($row['reg_date']) ?><br>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No industries registered yet.</p>
    <?php endif; ?>
</div>




    <!-- Footer -->
    <footer>
        <div class="footer-content" >
            <div id="logo" class="footer-logo" ><img src="logo.png" alt="GigConnect"></div>
            <ul>
                <li><a href="https://www.freejobalert.com/mp-government-jobs/" target="_blank">Jobs Information</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="mailto:ankitgour19168893@gmail.com">Contact Us</a></li> <!-- Email Link -->

                <li><a href="tel:+919399484597">+91 9399484597</a></li> <!-- Phone Number -->
            </ul>
            <div class="footer-social">
                <a href="https://www.linkedin.com/company/gigconnect-findyourfit" target="_blank"><img src="linkedin.png"></a>
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
    


 
    
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>

<script src="index.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body></html>

<?php
// Close the database connection
$conn->close();
?>
