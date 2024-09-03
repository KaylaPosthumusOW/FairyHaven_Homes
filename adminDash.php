<?php
session_start(); // Start the session
require 'config.php'; // Include your database configuration

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the session variable 'email' is set
if (!isset($_SESSION['email'])) {
    header("Location: pages/login.php"); // Redirect to login page if not logged in
    exit(); // Terminate the script to ensure redirection
}

// Retrieve session variables for user details
$email = $_SESSION['email'];

// Query to fetch admin user details using the email from the session
$sqlAdminDetails = "SELECT firstname, surname, email FROM users WHERE email = ?";
$stmtAdmin = $conn->prepare($sqlAdminDetails);
if ($stmtAdmin === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmtAdmin->bind_param("s", $email);
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();
$row = $resultAdmin->fetch_assoc();

// Query to fetch rejected listings
$sqlRejectedListings = "SELECT id, title, streetAddress, images, pricePm, availableDate, bedrooms, bathrooms, parkingSpace, lotSize FROM listing WHERE status = 'rejected'";
$resultRejectedListings = $conn->query($sqlRejectedListings);
if ($resultRejectedListings === false) {
    die('Query failed: ' . htmlspecialchars($conn->error));
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item']) && isset($_POST['listing_id'])) {
    $listingId = intval($_POST['listing_id']); // Ensure it's an integer

    // Prepare SQL query to delete the listing
    $sqlDeleteListing = "DELETE FROM listing WHERE id = ?";
    $stmt = $conn->prepare($sqlDeleteListing);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $listingId);

    if ($stmt->execute()) {
        // Successful deletion
        header("Location: adminDash.php?message=Listing%20deleted%20successfully");
        exit();
    } else {
        // Failed deletion
        header("Location: adminDash.php?error=Failed%20to%20delete%20listing");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="styles/adminDash.css">
    <link rel="stylesheet" href="styles/globalStyles.css">
    <link rel="stylesheet" href="styles/listings.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation -->
            <div class="col-2">
                <nav class="admin-nav">
                    <img class="admin-logo" src="assets/FHH-Logo_white.png" alt="FairyHaven Homes Logo">
                    <ul>
                        <li class="admin-link"><strong>Home</strong></li>
                        <li>
                            <a class="admin-link" href="<?php echo 'admin.pages/pendingListing.php'; ?>">Pending Listings</a>
                        </li>
                        <li class="admin-link">
                            <a class="admin-link" href="<?php echo 'admin.pages/addAgent.php'; ?>">Agents</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-9 mt-5">
                <h1 class="mb-4"><?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['surname']); ?>'s Profile</h1>
                
                <div class="row">
                    <div class="admin-profile col-5">
                        <div class="row mb-3">
                            <h3 class="mb-3">Details</h3>
                            <div class="col-5">
                                <p class="mb-2"><strong>First name</strong></p>
                                <p class="mb-2"><strong>Last name</strong></p>
                                <p><strong>Email address</strong></p>
                            </div>
                            <div class="col-7 info">
                                <p class="mb-2"><?php echo htmlspecialchars($row['firstname']); ?></p>
                                <p class="mb-2"><?php echo htmlspecialchars($row['surname']); ?></p>
                                <p><?php echo htmlspecialchars($row['email']); ?></p>
                            </div>
                            <button class="logOut mt-2">Log out</button>
                        </div>
                    </div>
                    <div class="listing-bg col-7">
                        <h3>Rejected Listings</h3>
                        <p class="text">Review Rejected Listings or Delete Listings</p>
                        <!-- rejected listings -->
                        <?php while ($listing = $resultRejectedListings->fetch_assoc()) { ?>
                            <div class="listing-card mb-3">
                                <a href="specificListing.php?id=<?php echo $listing['id']; ?>" class="text-decoration-none text-dark">
                                    <div class="row">
                                        <!-- Image -->
                                        <div class="col-4">
                                            <img class="listing-img" src="uploads/<?php echo htmlspecialchars($listing['images']); ?>" alt="Listing Image">
                                        </div>
                                        <!-- Content -->
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                                                    <p><?php echo htmlspecialchars($listing['streetAddress']); ?></p>
                                                </div>
                                                <div class="col-2">
                                                    <!-- delete listing form -->
                                                    <form action="adminDash.php" method="POST">
                                                        <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                                        <button type="submit" name="delete_item" class="btn delete-btn">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <div class="align2 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M888.13-717.13v474.26q0 37.78-26.61 64.39t-64.39 26.61H162.87q-37.78 0-64.39-26.61t-26.61-64.39v-474.26q0-37.78 26.61-64.39t64.39-26.61h634.26q37.78 0 64.39 26.61t26.61 64.39Zm-725.26 76.41h634.26v-76.41H162.87v76.41Zm0 160v237.85h634.26v-237.85H162.87Zm0 237.85v-474.26 474.26Z"/></svg>
                                                <p><?php echo htmlspecialchars($listing['pricePm']); ?></p>
                                            </div>
                                            <div class="align2">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M202.87-71.87q-37.78 0-64.39-26.61t-26.61-64.39v-554.26q0-37.78 26.61-64.39t64.39-26.61H240v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34q12.34 12.33 12.34 30.29v37.37h309v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34Q720-863.46 720-845.5v37.37h37.13q37.78 0 64.39 26.61t26.61 64.39v554.26q0 37.78-26.61 64.39t-64.39 26.61H202.87Zm0-91h554.26V-560H202.87v397.13Zm0-477.13h554.26v-77.13H202.87V-640Zm0 0v-77.13V-640ZM480-398.09q-17.81 0-29.86-12.05T438.09-440q0-17.81 12.05-29.86T480-481.91q17.81 0 29.86 12.05T521.91-440q0 17.81-12.05 29.86T480-398.09Zm-160 0q-17.81 0-29.86-12.05T278.09-440q0-17.81 12.05-29.86T320-481.91q17.81 0 29.86 12.05T361.91-440q0 17.81-12.05 29.86T320-398.09Zm320 0q-17.48 0-29.7-12.05-12.21-12.05-12.21-29.86t12.21-29.86q12.22-12.05 29.82-12.05t29.7 12.05q12.09 12.05 12.09 29.86t-12.05 29.86q-12.05 12.05-29.86 12.05Zm-160 160q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm-160 0q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm320 0q-17.48 0-29.7-12.21-12.21-12.22-12.21-29.82t12.21-29.7q12.22-12.09 29.82-12.09t29.7 12.05q12.09 12.05 12.09 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Z"/></svg>
                                                <p>Available From: <?php echo htmlspecialchars($listing['availableDate']); ?></p>
                                            </div>
                                            <div class="row">
                                                <div class="col-2 interior">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M80-200v-240q0-27 11-49t29-39v-112q0-50 35-85t85-35h160q23 0 43 8.5t37 23.5q17-15 37-23.5t43-8.5h160q50 0 85 35t35 85v112q18 17 29 39t11 49v240h-80v-80H160v80H80Zm440-360h240v-80q0-17-11.5-28.5T720-680H560q-17 0-28.5 11.5T520-640v80Zm-320 0h240v-80q0-17-11.5-28.5T400-680H240q-17 0-28.5 11.5T200-640v80Zm-40 200h640v-80q0-17-11.5-28.5T760-480H200q-17 0-28.5 11.5T160-440v80Zm640 0H160h640Z"/></svg>
                                                    <p><?php echo htmlspecialchars($listing['bedrooms']); ?></p>
                                                </div>
                                                <div class="col-2 interior">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M280-600q-33 0-56.5-23.5T200-680q0-33 23.5-56.5T280-760q33 0 56.5 23.5T360-680q0 33-23.5 56.5T280-600ZM200-80q-17 0-28.5-11.5T160-120q-33 0-56.5-23.5T80-200v-240h120v-30q0-38 26-64t64-26q20 0 37 8t31 22l56 62q8 8 15.5 15t16.5 13h274v-326q0-14-10-24t-24-10q-6 0-11.5 2.5T664-790l-50 50q5 17 2 33.5T604-676L494-788q14-9 30-11.5t32 3.5l50-50q16-16 36.5-25t43.5-9q48 0 81 33t33 81v326h80v240q0 33-23.5 56.5T800-120q0 17-11.5 28.5T760-80H200Zm-40-120h640v-160H160v160Zm0 0h640-640Z"/></svg>
                                                    <p><?php echo htmlspecialchars($listing['bathrooms']); ?></p>
                                                </div>
                                                <div class="col-2 interior">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M240-200v40q0 17-11.5 28.5T200-120h-40q-17 0-28.5-11.5T120-160v-320l84-240q6-18 21.5-29t34.5-11h440q19 0 34.5 11t21.5 29l84 240v320q0 17-11.5 28.5T800-120h-40q-17 0-28.5-11.5T720-160v-40H240Zm-8-360h496l-42-120H274l-42 120Zm-32 80v200-200Zm100 160q25 0 42.5-17.5T360-380q0-25-17.5-42.5T300-440q-25 0-42.5 17.5T240-380q0 25 17.5 42.5T300-320Zm360 0q25 0 42.5-17.5T720-380q0-25-17.5-42.5T660-440q-25 0-42.5 17.5T600-380q0 25 17.5 42.5T660-320Zm-460 40h560v-200H200v200Z"/></svg>
                                                    <p><?php echo htmlspecialchars($listing['parkingSpace']); ?></p>
                                                </div>
                                                <div class="col-3 interior2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M400-80H120q-33 0-56.5-23.5T40-160v-240h80v240h280v80Zm160 0v-80h280v-240h80v240q0 33-23.5 56.5T840-80H560ZM40-560v-240q0-33 23.5-56.5T120-880h280v80H120v240H40Zm800 0v-240H560v-80h280q33 0 56.5 23.5T920-800v240h-80Z"/></svg>
                                                    <p><?php echo htmlspecialchars($listing['lotSize']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                </div>
            
            
            </div>
                
        </div>
    </div>
</body>
</html>
