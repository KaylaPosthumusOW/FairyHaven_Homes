<?php
session_start(); // Start the session
require '../config.php';

// Check if the session variable 'email' is set
if (!isset($_SESSION['email'])) {
    header("Location: pages/login.php"); // Redirect to login page if the user is not logged in
    exit(); // Terminate the script to ensure redirection
}

// Retrieve session variables
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
$userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : ''; // Assuming userId is stored in the session

// Initialize filters
$forSaleRent = isset($_GET['forSaleRent']) ? $_GET['forSaleRent'] : '';
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$propType = isset($_GET['propType']) ? $_GET['propType'] : '';
$bedrooms = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
$maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';

// Construct the base query
$sql = "SELECT * FROM listing WHERE status = 'approved'";

// Initialize parameters array and types string
$params = [];
$types = '';

// Add filters to the query
if ($forSaleRent != '') {
    $sql .= " AND forSaleRent = ?";
    $params[] = $forSaleRent;
    $types .= 's';
}
if ($searchQuery != '') {
    $sql .= " AND (title LIKE ? OR streetAddress LIKE ?)";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
    $types .= 'ss';
}
if ($propType != '') {
    $sql .= " AND propType = ?";
    $params[] = $propType;
    $types .= 's';
}
if ($bedrooms != '') {
    $sql .= " AND bedrooms = ?";
    $params[] = $bedrooms;
    $types .= 'i';
}
if ($minPrice != '') {
    $sql .= " AND pricePm >= ?";
    $params[] = $minPrice;
    $types .= 'i';
}
if ($maxPrice != '') {
    $sql .= " AND pricePm <= ?";
    $params[] = $maxPrice;
    $types .= 'i';
}

// Prepare and execute the query for fetching listings
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Handle adding to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['listingId'])) {
        $listingId = $_POST['listingId'];  // Make sure this matches the actual input field name

        // Prepare and execute the query to add listing to wishlist
        $wishlistStmt = $conn->prepare("INSERT INTO wishlist (userId, listingId) VALUES (?, ?)");
        $wishlistStmt->bind_param('ii', $userId, $listingId);
        $wishlistStmt->execute();
        $wishlistStmt->close();

        // Optionally redirect to avoid resubmission
        header("Location: listing.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>listings page</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/listings.css">
    <link rel="stylesheet" href="../styles/globalStyles.css">
</head>
<body>

<!-- Nav Bar -->
<nav class="navbar navbar-expand-lg mb-5">
        <div class="container">
          <a class="navbar-brand" href="../assets/FHH-Logo.svg">
            <img src="../assets/FHH-Logo.svg">
          </a>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-5">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="<?php echo '../homepage.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
                    Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M160-200q-33 0-56.5-23.5T80-280v-247q0-16 6-30.5t17-25.5l114-114q11-11 25.5-17t30.5-6h7v-40q0-17 11.5-28.5T320-800q17 0 28.5 11.5T360-760v40h327q16 0 30.5 6t25.5 17l114 114q11 11 17 25.5t6 30.5v247q0 33-23.5 56.5T800-200H160Zm480-80h160v-247l-80-80-80 80v247Zm-80 0v-200H160v200h400Z"/></svg>
                    <strong>Properties</strong>
                </a>
              </li>
            </ul>
            <button type="button" class="btn add">
                <a class="btn-link" href="<?php echo 'addProp.php'; ?>">Add Property</a>
            </button>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo 'profile.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                    <?php echo htmlspecialchars($firstname . " " . $surname); ?>
                </a>
              </li>
          </div>
        </div>
      </nav>

    <!-- Filtering -->
        <form method="GET" action="listing.php" class="container filtering mb-4"> <!-- Changed to a form with method GET -->
            <div class="row">
                <div class="col-3 mb-3">
                    <select class="form-select filter" id="forSaleRent" name="forSaleRent">
                        <option value="sale">For Sale</option>
                        <option value="rent">To Rent</option>
                    </select>
                </div>

                <div class="col-8 mb-3">
                    <input type="text" class="form-control filter" id="searchQuery" name="searchQuery" placeholder="Search for a City or State">
                </div>

                <div class="col-1 mb-3">
                    <button type="submit" class="btn btn-primary search filter">Apply</button>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <select class="form-select filter" id="propType" name="propType">
                        <option value="">Property Type</option>
                        <option value="Treehouse">Treehouse</option>
                        <option value="Flower-den">Flower-den</option>
                        <option value="Mountainside Cottage">Mountainside Cottage</option>
                        <option value="Underwater Cove">Underwater Cove</option>
                        <option value="Mushroom House">Mushroom House</option>
                    </select>
                </div>

                <div class="col-2">
                    <select class="form-select filter" id="bedrooms" name="bedrooms">
                        <option value="">Bedrooms</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>

                <div class="col-2">
                    <input type="number" class="form-control filter" id="minPrice" name="minPrice" placeholder="Minimum Price">
                </div>

                <div class="col-2">
                    <input type="number" class="form-control filter" id="maxPrice" name="maxPrice" placeholder="Maximum Price">
                </div>

                <div class="col-2">
                    <button class="clear" id="clear-filters">Clear All Filters</button>
                </div>
            </div>
        </form>

    <!-- header -->
    <div class="container mb-3">
            <div class="center-heading">
            <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#2E2212"><path d="M480-510.74ZM225.09-145.87q-33.26 0-56.24-22.98-22.98-22.98-22.98-56.24v-312.3l-43.04 33q-12.44 9.69-27.74 7.41-15.31-2.28-26.13-16.57-10.26-12.49-8.04-28.92 2.23-16.43 15.73-26.31l375.48-288.74q10.91-8.26 23.22-12.39 12.3-4.13 24.65-4.13 12.35 0 24.65 4.13 12.31 4.13 23.22 12.39l376.48 289.3q12.73 10.45 14.56 26.31 1.83 15.87-8.43 28.69-10.26 12.83-26.5 15.39-16.24 2.57-29.07-7.69L480-795.39 224.52-598.32v373.8h146.74q16.93 0 28.55 11.5 11.62 11.5 11.62 28.42t-11.38 27.83q-11.39 10.9-28.22 10.9H225.09Zm394.82 64.61q-8.13 0-14.82-2.85-6.7-2.85-12.83-8.98L474.17-211.17q-11.82-11.83-11.82-27.79 0-15.95 12.11-28.34 11.59-11.83 27.7-11.83t28.14 11.83l89.74 90.3 199.22-198.22q11.83-11.26 27.78-11.54 15.96-.28 27.79 11.62 11.82 11.91 11.82 27.79 0 15.87-11.82 27.7L648.39-93.09q-6.13 6.13-13.55 8.98-7.42 2.85-14.93 2.85Z"/></svg>
                <h1>Properties for Sale</h1>
            </div>
    </div>

    <div class="container listing-bg mb-5">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="listing-card mb-3">
                <a href="specificListing.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                <div class="row">
                    <!-- image -->
                    <div class="col-3">
                        <img class="listing-img" src="../uploads/<?php echo htmlspecialchars($row['images']); ?>" alt="Listing Image">
                    </div>
                    <!-- content -->
                    <div class="col-7">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['streetAddress']); ?></p>

                        <div class="align">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M888.13-717.13v474.26q0 37.78-26.61 64.39t-64.39 26.61H162.87q-37.78 0-64.39-26.61t-26.61-64.39v-474.26q0-37.78 26.61-64.39t64.39-26.61h634.26q37.78 0 64.39 26.61t26.61 64.39Zm-725.26 76.41h634.26v-76.41H162.87v76.41Zm0 160v237.85h634.26v-237.85H162.87Zm0 237.85v-474.26 474.26Z"/></svg>
                            <p><?php echo htmlspecialchars($row['pricePm']); ?></p>
                        </div>
                        <div class="align2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="http://www.w3.org/2000/svg" width="24px" fill="#2E2212"><path d="M202.87-71.87q-37.78 0-64.39-26.61t-26.61-64.39v-554.26q0-37.78 26.61-64.39t64.39-26.61H240v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34q12.34 12.33 12.34 30.29v37.37h309v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34Q720-863.46 720-845.5v37.37h37.13q37.78 0 64.39 26.61t26.61 64.39v554.26q0 37.78-26.61 64.39t-64.39 26.61H202.87Zm0-91h554.26V-560H202.87v397.13Zm0-477.13h554.26v-77.13H202.87V-640Zm0 0v-77.13V-640ZM480-398.09q-17.81 0-29.86-12.05T438.09-440q0-17.81 12.05-29.86T480-481.91q17.81 0 29.86 12.05T521.91-440q0 17.81-12.05 29.86T480-398.09Zm-160 0q-17.81 0-29.86-12.05T278.09-440q0-17.81 12.05-29.86T320-481.91q17.81 0 29.86 12.05T361.91-440q0 17.81-12.05 29.86T320-398.09Zm320 0q-17.48 0-29.7-12.05-12.21-12.05-12.21-29.86t12.21-29.86q12.22-12.05 29.82-12.05t29.7 12.05q12.09 12.05 12.09 29.86t-12.05 29.86q-12.05 12.05-29.86 12.05Zm-160 160q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm-160 0q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm320 0q-17.48 0-29.7-12.21-12.21-12.22-12.21-29.82t12.21-29.7q12.22-12.09 29.82-12.09t29.7 12.05q12.09 12.05 12.09 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Z"/></svg>
                            <p><?php echo htmlspecialchars($row['availableDate']); ?></p>
                        </div>
                        <div class="row">
                            <div class="col-1 interior">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="http://www.w3.org/2000/svg" width="24px" fill="#2E2212"><path d="M80-200v-240q0-27 11-49t29-39v-112q0-50 35-85t85-35h160q23 0 43 8.5t37 23.5q17-15 37-23.5t43-8.5h160q50 0 85 35t35 85v112q18 17 29 39t11 49v240h-80v-80H160v80H80Zm440-360h240v-80q0-17-11.5-28.5T720-680H560q-17 0-28.5 11.5T520-640v80Zm-320 0h240v-80q0-17-11.5-28.5T400-680H240q-17 0-28.5 11.5T200-640v80Zm-40 200h640v-80q0-17-11.5-28.5T760-480H200q-17 0-28.5 11.5T160-440v80Zm640 0H160h640Z"/></svg>
                                <p><?php echo htmlspecialchars($row['bedrooms']); ?></p>
                            </div>
                            <div class="col-1 interior">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="http://www.w3.org/2000/svg" width="24px" fill="#2E2212"><path d="M280-600q-33 0-56.5-23.5T200-680q0-33 23.5-56.5T280-760q33 0 56.5 23.5T360-680q0 33-23.5 56.5T280-600ZM200-80q-17 0-28.5-11.5T160-120q-33 0-56.5-23.5T80-200v-240h120v-30q0-38 26-64t64-26q20 0 37 8t31 22l56 62q8 8 15.5 15t16.5 13h274v-326q0-14-10-24t-24-10q-6 0-11.5 2.5T664-790l-50 50q5 17 2 33.5T604-676L494-788q14-9 30-11.5t32 3.5l50-50q16-16 36.5-25t43.5-9q48 0 81 33t33 81v326h80v240q0 33-23.5 56.5T800-120q0 17-11.5 28.5T760-80H200Zm-40-120h640v-160H160v160Zm0 0h640-640Z"/></svg>
                                <p><?php echo htmlspecialchars($row['bathrooms']); ?></p>
                            </div>
                            <div class="col-1 interior">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="http://www.w3.org/2000/svg" width="24px" fill="#2E2212"><path d="M240-200v40q0 17-11.5 28.5T200-120h-40q-17 0-28.5-11.5T120-160v-320l84-240q6-18 21.5-29t34.5-11h440q19 0 34.5 11t21.5 29l84 240v320q0 17-11.5 28.5T800-120h-40q-17 0-28.5-11.5T720-160v-40H240Zm-8-360h496l-42-120H274l-42 120Zm-32 80v200-200Zm100 160q25 0 42.5-17.5T360-380q0-25-17.5-42.5T300-440q-25 0-42.5 17.5T240-380q0 25 17.5 42.5T300-320Zm360 0q25 0 42.5-17.5T720-380q0-25-17.5-42.5T660-440q-25 0-42.5 17.5T600-380q0 25 17.5 42.5T660-320Zm-460 40h560v-200H200v200Z"/></svg>
                                <p><?php echo htmlspecialchars($row['parkingSpace']); ?></p>
                            </div>
                            <div class="col-2 interior2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="http://www.w3.org/2000/svg" width="24px" fill="#2E2212"><path d="M400-80H120q-33 0-56.5-23.5T40-160v-240h80v240h280v80Zm160 0v-80h280v-240h80v240q0 33-23.5 56.5T840-80H560ZM40-560v-240q0-33 23.5-56.5T120-880h280v80H120v240H40Zm800 0v-240H560v-80h280q33 0 56.5 23.5T920-800v240h-80Z"/></svg>
                                <p><?php echo htmlspecialchars($row['lotSize']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <form method="POST" action="listing.php">
                            <input type="hidden" name="UserId" value="<?php echo htmlspecialchars($userId); ?>">
                            <input type="hidden" name="listingId" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" class="btn wishlist-button"></button>
                        </form>
                    </div>
                </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No listings available.</p>
    <?php endif; ?>
    <?php $conn->close(); ?>
</div>

<!-- Footer -->
<?php include '../components/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YmQWgM0g7H3+rxRWxjT0zuGzWn6FEF9KSpZZ1c1g57HDv3j3kxjcG0aGSkUQf1aXI" crossorigin="anonymous"></script>

    
</body>
</html>



