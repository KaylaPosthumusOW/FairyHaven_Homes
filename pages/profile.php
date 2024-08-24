<?php
session_start(); // Start the session
require '../config.php';

// Check if the session variable 'email' is set
if(!isset($_SESSION['email'])){
    header("Location: pages/login.php"); // Redirect to login page if the user is not logged in
    exit(); // Terminate the script to ensure redirection
}

// Retrieve session variables
$email = $_SESSION['email'];

// Query to fetch user details based on email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Set user_id in session if not already set
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = $row['user_id'];
    }
} else {
    // Handle the case where no user is found
    echo "No user found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedFirstname = $_POST['firstname'];
    $updatedSurname = $_POST['surname'];
    $updatedEmail = $_POST['email'];

    // SQL query to update the user information
    $sqlUpdate = "UPDATE users SET firstname=?, surname=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sssi", $updatedFirstname, $updatedSurname, $updatedEmail, $_SESSION['user_id']);

    if ($stmt->execute()) {
        // Update the session variables
        $_SESSION['firstname'] = $updatedFirstname;
        $_SESSION['surname'] = $updatedSurname;
        $_SESSION['email'] = $updatedEmail;

        // Optionally, refresh the page to reflect changes
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

     <!-- Bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/listings.css">
    <link rel="stylesheet" href="../styles/profile.css">
</head>
<body>

    <!-- JavaScript for Edit/Save functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButton = document.getElementById('editButton');
            const saveButton = document.getElementById('saveButton');
            const firstnameField = document.getElementById('firstname');
            const surnameField = document.getElementById('surname');
            const emailField = document.getElementById('email');

            editButton.addEventListener('click', function() {
                firstnameField.removeAttribute('readonly');
                surnameField.removeAttribute('readonly');
                emailField.removeAttribute('readonly');

                editButton.classList.add('d-none');
                saveButton.classList.remove('d-none');
            });
        });
    </script>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
          <a class="navbar-brand" href="../assets/FHH-Logo.svg">
            <img src="../assets/FHH-Logo.svg">
          </a>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-5">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo '../homepage.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
                    Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo 'listing.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M160-200q-33 0-56.5-23.5T80-280v-247q0-16 6-30.5t17-25.5l114-114q11-11 25.5-17t30.5-6h7v-40q0-17 11.5-28.5T320-800q17 0 28.5 11.5T360-760v40h327q16 0 30.5 6t25.5 17l114 114q11 11 17 25.5t6 30.5v247q0 33-23.5 56.5T800-200H160Zm480-80h160v-247l-80-80-80 80v247Zm-80 0v-200H160v200h400Z"/></svg>
                    Properties
                </a>
              </li>
            </ul>
            <button type="button" class="btn add">
                <a class="btn-link" href="<?php echo 'addProp.php'; ?>">Add Property</a>
            </button>
            <li class="nav-item">
                <a class="nav-link" href="signIn.html">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                    <strong><?php echo htmlspecialchars($row['firstname']); ?> <?php echo htmlspecialchars($row['surname']); ?></strong>
                </a>
              </li>
          </div>
        </div>
    </nav>

    <!-- heading -->
    <div class="container mb-3">
            <div class="center-heading">
            <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#2E2212"><path d="M349.74-472.43ZM145.09-105.87q-33.26 0-56.24-22.98-22.98-22.98-22.98-56.24v-382.65q0-18.65 8.17-35.34Q82.2-619.77 97.22-631l254.91-191.61q11.12-7.69 23.32-11.54 12.21-3.85 24.79-3.85 12.59 0 24.61 3.85t23.02 11.54l139.56 104.96q15.73 11.85 16.06 27.23.34 15.38-8.36 27.2-9.26 12.39-24.43 16.52-15.18 4.13-31.14-7.69L400-758.78 145.09-567.55v382.46h149.65q16.71 0 28.16 11.79 11.45 11.78 11.45 28.56-.57 16.78-11.96 27.83-11.38 11.04-28.22 11.04H145.09Zm494.91-213q54.37 0 106.04 14.29 51.68 14.29 98.35 41.84 19.7 11.57 30.76 31.76 11.07 20.19 11.07 42.3v16q0 28.12-19.35 47.46-19.35 19.35-47.44 19.35H460.57q-28.09 0-47.44-19.35-19.35-19.34-19.35-47.45v-16q0-22.11 11.07-42.03 11.06-19.91 30.76-32.04 46.56-27 98.29-41.56 51.73-14.57 106.1-14.57ZM474.7-175.48h331.17v-29q-39.17-20.61-80.91-32.69-41.74-12.09-85.24-12.09-43.31 0-84.58 12.59-41.27 12.58-80.44 33.19v28Zm165.53-178.3q-52.49 0-89.47-36.75-36.98-36.74-36.98-89.24 0-52.49 36.75-89.47 36.74-36.98 89.24-36.98 52.49 0 89.47 36.75 36.98 36.74 36.98 89.24 0 52.49-36.75 89.47-36.74 36.98-89.24 36.98Zm-.17-69.61q24.08 0 40.32-16.29 16.23-16.3 16.23-40.38 0-24.08-16.29-40.32-16.3-16.23-40.38-16.23-24.08 0-40.32 16.29-16.23 16.3-16.23 40.38 0 24.08 16.29 40.32 16.3 16.23 40.38 16.23Zm.51 247.91Z"/></svg>
                <h1>Profile Page</h1>
            </div>
    </div>

    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="box-profile">
                    <form id="profileForm" method="POST" action="">
                        <div class="row">
                            <div class="col-10 mb-4">
                                <h2>Details</h2>
                            </div>
                            <div class="col-2">
                                <button type="button" id="editButton" class="btn btn-link edit">Edit</button>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <p><strong>Firstname</strong></p>
                                <p><strong>Lastname</strong></p>
                                <p><strong>Email address</strong></p>
                            </div>
                            <div class="col-6 info">
                                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($row['firstname']); ?>" readonly>
                                <input type="text" name="surname" id="surname" class="form-control mt-2" value="<?php echo htmlspecialchars($row['surname']); ?>" readonly>
                                <input type="email" name="email" id="email" class="form-control mt-2" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                            </div>
                        </div>
                        <button type="submit" id="saveButton" class="btn save d-none">Save</button>
                    </form>
                    <button class="logOut mt-4">
                        <p>Log Out</p>
                    </button>
                </div>
                </div>
                <div class="col-7"></div>
        </div>
    </div>
    
</body>
</html>