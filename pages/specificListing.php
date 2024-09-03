<?php
session_start(); // Start the session
require '../config.php';

// Check if the session variable 'email' is set
if(!isset($_SESSION['email'])){
    header("Location: pages/login.php"); // Redirect to login page if the user is not logged in
    exit(); // Terminate the script to ensure redirection
}

// Retrieve session variables
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';

// Fetch the listing ID from the URL
$listingId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($listingId > 0) {
    // Fetch the specific listing details from the database, including agent details
    $sql = "SELECT listing.*, agent.firstName AS agent_name, agent.email AS agent_email, agent.number AS agent_phone , agent.lastName AS agent_surname
            FROM listing 
            LEFT JOIN agent ON listing.agentId = agent.AgentId 
            WHERE listing.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $listing = $result->fetch_assoc(); // Store the result in $listing array
    } else {
        echo "Listing not found.";
        exit();
    }
} else {
    echo "Invalid listing ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($listing['title']); ?> - Specific Listing</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    

    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/specificListing.css">
    <link rel="stylesheet" href="../styles/listings.css">
    <link rel="stylesheet" href="../styles/globalStyles.css">
    <!-- <link rel="stylesheet" href="../styles/addProp.css"> -->

</head>
<body>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg mb-3">
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
                    <a class="nav-link" href="<?php echo 'listing.php'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M160-200q-33 0-56.5-23.5T80-280v-247q0-16 6-30.5t17-25.5l114-114q11-11 25.5-17t30.5-6h7v-40q0-17 11.5-28.5T320-800q17 0 28.5 11.5T360-760v40h327q16 0 30.5 6t25.5 17l114 114q11 11 17 25.5t6 30.5v247q0 33-23.5 56.5T800-200H160Zm480-80h160v-247l-80-80-80 80v247Zm-80 0v-200H160v200h400Z"/></svg>
                        <strong>Properties</strong>
                    </a>
                </li>
                </ul>
                <button type="button" class="btn add">
                    <a class="btn-link" href="<?php echo 'addProp.php'; ?>">Add Property</a>
                </button>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo 'profile.php'; ?>"">
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                        <?php echo htmlspecialchars($firstname . " " . $surname); ?>
                    </a>
                </li>
          </div>
        </div>
      </nav>

      <div class="container mb-3">
        <!-- Back Button -->
         <a type="button" href="<?php echo 'listing.php'; ?>">
            <button class="back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </button>
        </a>
        
      </div>

      <!-- Carousel -->
      <div class="container mb-4">
        <div class="carousel slide">
            <div class="carousel-inner">
                <img src="../uploads/<?php echo htmlspecialchars($listing['images']); ?>" class="images" alt="...">
            </div>
        </div>
      </div>

      <!-- Listing Information -->
       <div class="container mb-4 info">
        <div class="row">
            <!-- row 1 -->
            <div class="col-7">
                <!-- Property basics -->
                <div class="row box2 mb-3">
                <div class="col-7">
                        <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                        <p class="address-color"><small><?php echo htmlspecialchars($listing['streetAddress']); ?></small></p>

                        <div class="text align2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M888.13-717.13v474.26q0 37.78-26.61 64.39t-64.39 26.61H162.87q-37.78 0-64.39-26.61t-26.61-64.39v-474.26q0-37.78 26.61-64.39t64.39-26.61h634.26q37.78 0 64.39 26.61t26.61 64.39Zm-725.26 76.41h634.26v-76.41H162.87v76.41Zm0 160v237.85h634.26v-237.85H162.87Zm0 237.85v-474.26 474.26Z"/></svg>
                            <p><?php echo htmlspecialchars($listing['pricePm']); ?></p>
                        </div>
                        <div class="text align2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M202.87-71.87q-37.78 0-64.39-26.61t-26.61-64.39v-554.26q0-37.78 26.61-64.39t64.39-26.61H240v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34q12.34 12.33 12.34 30.29v37.37h309v-37.37q0-17.96 12.46-30.29 12.45-12.34 30.41-12.34t30.29 12.34Q720-863.46 720-845.5v37.37h37.13q37.78 0 64.39 26.61t26.61 64.39v554.26q0 37.78-26.61 64.39t-64.39 26.61H202.87Zm0-91h554.26V-560H202.87v397.13Zm0-477.13h554.26v-77.13H202.87V-640Zm0 0v-77.13V-640ZM480-398.09q-17.81 0-29.86-12.05T438.09-440q0-17.81 12.05-29.86T480-481.91q17.81 0 29.86 12.05T521.91-440q0 17.81-12.05 29.86T480-398.09Zm-160 0q-17.81 0-29.86-12.05T278.09-440q0-17.81 12.05-29.86T320-481.91q17.81 0 29.86 12.05T361.91-440q0 17.81-12.05 29.86T320-398.09Zm320 0q-17.48 0-29.7-12.05-12.21-12.05-12.21-29.86t12.21-29.86q12.22-12.05 29.82-12.05t29.7 12.05q12.09 12.05 12.09 29.86t-12.05 29.86q-12.05 12.05-29.86 12.05Zm-160 160q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm-160 0q-17.81 0-29.86-12.21-12.05-12.22-12.05-29.82t12.05-29.7q12.05-12.09 29.86-12.09t29.86 12.05q12.05 12.05 12.05 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Zm320 0q-17.48 0-29.7-12.21-12.21-12.22-12.21-29.82t12.21-29.7q12.22-12.09 29.82-12.09t29.7 12.05q12.09 12.05 12.09 29.86 0 17.48-12.05 29.7-12.05 12.21-29.86 12.21Z"/></svg>
                            <p><?php echo htmlspecialchars($listing['availableDate']); ?></p>
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
                            <div class="col-4 interior2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2E2212"><path d="M400-80H120q-33 0-56.5-23.5T40-160v-240h80v240h280v80Zm160 0v-80h280v-240h80v240q0 33-23.5 56.5T840-80H560ZM40-560v-240q0-33 23.5-56.5T120-880h280v80H120v240H40Zm800 0v-240H560v-80h280q33 0 56.5 23.5T920-800v240h-80Z"/></svg>
                                <p><?php echo htmlspecialchars($listing['lotSize']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="row box2 mb-3">
                            <h3>Property Details</h3>
                            <div class="my-3">
                                <label for="title" class="form-label">Property Title</label>
                                <div class="text-bg">
                                    <p><?php echo htmlspecialchars($listing['title']); ?></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Property Description</label>
                                <div class="text-bg description">
                                    <p><?php echo htmlspecialchars($listing['description']); ?></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="postalCode" class="form-label">Property Type</label>
                                <div class="text-bg">
                                    <p><?php echo htmlspecialchars($listing['propType']); ?></p>
                                </div>
                            </div>
                </div>

                <!-- Agent Details -->
                <div class="row box3 mb-3">
                            <h2 class="agent mb-4">Contact Agent</h2>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>First and Last name</strong></p>
                                    <p><strong>Email address</strong></p>
                                    <p><strong>Phone number</strong></p>
                                    <p><strong>Agency</strong></p>
                                </div>
                                <div class="email_text col-6">
                                    <p><?php echo htmlspecialchars($listing['agent_name']); ?> <?php echo htmlspecialchars($listing['agent_surname']); ?></p>
                                    <p><a href="mailto:<?php echo htmlspecialchars($listing['agent_email']); ?>"><?php echo htmlspecialchars($listing['agent_email']); ?></a></p>
                                    <p><?php echo htmlspecialchars($listing['agent_phone']); ?></p>
                                    <p>FairyHaven Homes</p>
                                </div>
                            </div>
                </div>

            </div>
            <!-- row 2 -->
            <div class="col-5">
                <!-- Interior Box -->
                <div class="box2 mb-3">
                    <h3>Interior</h3>
                        <div class="my-3">
                            <label for="title" class="form-label">Total Floors</label>
                                <div class="text-bg">
                                    <p><?php echo htmlspecialchars($listing['totFloor']); ?></p>
                                </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Floor Size</label>
                                <div class="text-bg">
                                    <p><?php echo htmlspecialchars($listing['floorSize']); ?></p>
                                </div>
                        </div>
                        <div class="my-1">
                            <label for="totFloor" class="form-label">Rooms</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['bedrooms']); ?></p></div>
                            <label class="form-label mx-2 my-2">Bedrooms</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['bathrooms']); ?></p></div>
                            <label class="form-label mx-2 my-2">Bathrooms</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['kitchens']); ?></p></div>
                            <label class="form-label mx-2 my-2">Kitchens</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['diningRooms']); ?></p></div>
                            <label class="form-label mx-2 my-2">Dining Rooms</label>
                        </div>
                        <div class="align">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['basements']); ?></p></div>
                            <label class="form-label mx-2 my-2">Basements</label>
                        </div>
                </div>
                <div class="box2">
                    <!-- Exterior Box -->
                    <h3>Exterior</h3>
                        <div class="mb-3">
                            <label for="title" class="form-label">Lot Size</label>
                                <div class="text-bg">
                                    <p><?php echo htmlspecialchars($listing['lotSize']); ?></p>
                                </div>
                        </div>
                        <div class="my-1">
                            <label for="nameSurname" class="form-label">Exterior Spaces</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['parkingSpace']); ?></p></div>
                            <label class="form-label mx-2 my-2">Parking Spaces</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['gardens']); ?></p></div>
                            <label class="form-label mx-2 my-2">Gardens</label>
                        </div>
                        <div class="align mb-3">
                            <div class="number-input"><p><?php echo htmlspecialchars($listing['patio']); ?></p></div>
                            <label class="form-label mx-2 my-2">Patio / Deck</label>
                        </div>
                </div>
             </div>
            </div>
        </div>
       </div>

    <!-- Footer -->
 <?php include '../components/footer.php'; ?>

      
    
</body>
</html>