<?php 
session_start(); // Start the session
require 'config.php';

// Check if the session variables 'firstname' and 'surname' are set
if(!isset($_SESSION['email'])){
    header("Location: pages/login.php"); // Redirect to login page if the user is not logged in
    exit(); // Terminate the script to ensure redirection
}

$firstname = $_SESSION['firstname'];
$surname = $_SESSION['surname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FairyHaven Homes</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/homepage.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/globalStyles.css">

</head>
<body>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="../assets/FHH-Logo.svg">
            <img src="assets/FHH-Logo.svg" alt="FairyHaven Homes Logo">
          </a>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-5">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
                    <strong>Home</strong>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo 'pages/listing.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M160-200q-33 0-56.5-23.5T80-280v-247q0-16 6-30.5t17-25.5l114-114q11-11 25.5-17t30.5-6h7v-40q0-17 11.5-28.5T320-800q17 0 28.5 11.5T360-760v40h327q16 0 30.5 6t25.5 17l114 114q11 11 17 25.5t6 30.5v247q0 33-23.5 56.5T800-200H160Zm480-80h160v-247l-80-80-80 80v247Zm-80 0v-200H160v200h400Z"/></svg>
                    Properties
                </a>
              </li>
            </ul>
            
            <button type="button" class="btn add">
                <a class="btn-link" href="<?php echo 'pages/addProp.php'; ?>">Add Property</a>
            </button>
            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo 'pages/profile.php'; ?>"">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                    <?php echo htmlspecialchars($firstname . " " . $surname); ?>
                </a>
            </li>
          </div>
        </div>
      </nav>

      <!-- Homepage header -->
       <div class="header-content">
        <h1>FairyHaven Homes</h1>
        <p>Welcome to FairyHaven Homes, explore our magical listings and find your perfect retreat in a world where imagination meets comfort.</p>
        <button type="button" class="btn primary">
          <a class="btn-link" href="<?php echo 'pages/listing.php'; ?>">Explore Listing</a>
        </button>
        <img class="homepage-img" src="assets/homepage-img.png" alt="Homepage Image">
       </div>
    
       <!-- Footer -->
        <?php include 'components/footer.php'; ?>
</body>
</html>
