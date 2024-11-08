<?php 
session_start(); // Start the session
require '../config.php'; // Include the config file for database connection

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Prepare SQL query to find the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['firstname'] = $user['firstname']; 
            $_SESSION['surname'] = $user['surname']; 
            $_SESSION['email'] = $user['email']; 
            $_SESSION['usertype'] = $user['usertype']; // Store usertype in session

            // Redirect based on usertype
            if ($user['userType'] === 'admin') {
                header("Location: ../adminDash.php"); // Redirect to admin dashboard
            } else {
                header("Location: ../homepage.php"); // Redirect to homepage for standard users
            }
            exit();
        } else {
            // Error message for wrong password
            echo "Invalid email or password - password issue";
        }
    } else {
        // Error message for wrong email
        echo "Invalid email or password - email issue";
    }
    
    $stmt->close();
    $conn->close(); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="../styles/signInUp.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     
</head>
<body>

    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../assets/FHH-Logo.svg">
                <img src="../assets/FHH-Logo.svg" alt="FairyHaven Homes Logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-5">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M160-200q-33 0-56.5-23.5T80-280v-247q0-16 6-30.5t17-25.5l114-114q11-11 25.5-17t30.5-6h7v-40q0-17 11.5-28.5T320-800q17 0 28.5 11.5T360-760v40h327q16 0 30.5 6t25.5 17l114 114q11 11 17 25.5t6 30.5v247q0 33-23.5 56.5T800-200H160Zm480-80h160v-247l-80-80-80 80v247Zm-80 0v-200H160v200h400Z"/></svg>
                            Properties
                        </a>
                    </li>
                </ul>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                        Sign In
                    </a>
                </li>
            </div>
        </div>
    </nav>

    <!-- Log In Form -->
    <div class="row">
        <!-- Image -->
        <div class="col-4">
            <img class="signIn-img" src="../assets/SignInUp.png" alt="Sign In Image">
        </div>
        <!-- Form Col -->
        <div class="col-8 signIn-form">
            <div class="row">
                <div class="col-2">
                    <img style="margin-left:-30px" src="../assets/FHH-Logo.svg" alt="FairyHaven Homes Logo">
                </div>
                <div class="col-10 my-2">
                    <h2>FairyHaven Homes</h2>
                </div>
            </div>

            <p class="welcome">Welcome back to FairyHaven Homes, login in and pick up where you left off.</p>
            
        
            <!-- Form -->
            <form action="logIn.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="input-group-text" id="toggleIcon"><i class="fas fa-eye-slash"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn">Log In</button>
                <p>Don't have an account? 
                <a class="link" href="<?php echo '../pages/signUp.php'; ?>">Sign Up</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Footer -->
 <?php include '../components/footer.php'; ?>

 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleIcon.addEventListener('click', function () {
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                toggleIcon.querySelector('i').classList.toggle('fa-eye');
                toggleIcon.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>


</body>
</html>
