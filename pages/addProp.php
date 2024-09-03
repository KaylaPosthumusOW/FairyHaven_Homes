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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $streetAddress = mysqli_real_escape_string($conn, $_POST['streetAddress']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $postalCode = mysqli_real_escape_string($conn, $_POST['postalCode']);
    $propType = mysqli_real_escape_string($conn, $_POST['propType']);
    $availableDate = mysqli_real_escape_string($conn, $_POST['availableDate']);
    $forSaleRent = mysqli_real_escape_string($conn, $_POST['forSaleRent']);
    $pricePm = mysqli_real_escape_string($conn, $_POST['pricePm']);
    $totFloor = mysqli_real_escape_string($conn, $_POST['totFloor']);
    $floorSize = mysqli_real_escape_string($conn, $_POST['floorSize']);
    $bedrooms = mysqli_real_escape_string($conn, $_POST['bedrooms']);
    $bathrooms = mysqli_real_escape_string($conn, $_POST['bathrooms']);
    $kitchens = mysqli_real_escape_string($conn, $_POST['kitchens']);
    $diningRooms = mysqli_real_escape_string($conn, $_POST['diningRooms']);
    $basements = mysqli_real_escape_string($conn, $_POST['basements']);
    $lotSize = mysqli_real_escape_string($conn, $_POST['lotSize']);
    $parkingSpace = mysqli_real_escape_string($conn, $_POST['parkingSpace']);
    $gardens = mysqli_real_escape_string($conn, $_POST['gardens']);
    $patio = mysqli_real_escape_string($conn, $_POST['patio']);

    $wifi = isset($_POST['wifi']) ? 1 : 0;
    $airConditioning = isset($_POST['airConditioning']) ? 1 : 0;
    $floorHeating = isset($_POST['floorHeating']) ? 1 : 0;
    $pool = isset($_POST['pool']) ? 1 : 0;
    $fitnessCentr = isset($_POST['fitnessCentr']) ? 1 : 0;
    $gardenServ = isset($_POST['gardenServ']) ? 1 : 0;
    $undercPark = isset($_POST['undercPark']) ? 1 : 0;
    $gatedCom = isset($_POST['gatedCom']) ? 1 : 0;

    $agentId = isset($_POST['agentId']) ? (int)$_POST['agentId'] : NULL;

    // Handle file upload
    $target_dir = "../uploads/";
    if (isset($_FILES['images']) && $_FILES['images']['error'] == UPLOAD_ERR_OK) {
        $images = $_FILES['images']['name'];
        $target_file = $target_dir . basename($images);

        // Verify that the file is an image
        $check = getimagesize($_FILES["images"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["images"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an issue uploading your file.";
                exit(); // Exiting to prevent DB insertion if the image failed to upload
            }
        } else {
            echo "File is not an image.";
            exit(); // Exiting if the image is incorrect
        }
    } else {
        echo "No file uploaded or file upload error.";
        exit(); // Exiting if the file was not uploaded correctly
    }

    // SQL query to insert the property into the database
    $sql = "INSERT INTO listing (
        title, description, propType, availableDate, totFloor, floorSize, bedrooms, bathrooms, 
        kitchens, diningRooms, basements, wifi, airConditioning, floorHeating, pool, fitnessCentr, 
        gardenServ, undercPark, gatedCom, forSaleRent, pricePm, lotSize, parkingSpace, gardens, 
        patio, images, agentId, streetAddress, city, state, postalCode
    ) VALUES (
        '$title',
        '$description',
        '$propType',
        '$availableDate',
        '$totFloor',
        '$floorSize',
        '$bedrooms',
        '$bathrooms',
        '$kitchens',
        '$diningRooms',
        '$basements',
        $wifi,
        $airConditioning,
        $floorHeating,
        $pool,
        $fitnessCentr,
        $gardenServ,
        $undercPark,
        $gatedCom,
        '$forSaleRent',
        '$pricePm',
        '$lotSize',
        '$parkingSpace',
        '$gardens',
        '$patio',
        '$images',
        " . ($agentId ? $agentId : 'NULL') . ",
        '$streetAddress',
        '$city',
        '$state',
        '$postalCode'
    )";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('New property added successfully');</script>";
        // Optionally, redirect to a confirmation page
        header("Location: ../homepage.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Listing</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/addProp.css">
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
            <!-- <button type="button" class="btn add">
                <a class="btn-link" href="<?php echo 'addProp.php'; ?>">Add Property</a>
            </button> -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo 'profile.php'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#2E2212"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                    <?php echo htmlspecialchars($firstname . " " . $surname); ?>
                </a>
              </li>
          </div>
        </div>
      </nav>

      <!-- Heading -->
       <div class="container mb-3">
       <form method="POST" action="addProp.php" enctype="multipart/form-data">
        <div class="row">
        
            <div class="col-10 center-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#2E2212"><path d="M698-279v83q0 11.4 8.44 19.2 8.45 7.8 19.7 7.8 11.26 0 19.06-8.4 7.8-8.4 7.8-19.6v-82h83q11.4 0 19.2-8.44 7.8-8.45 7.8-19.7 0-11.26-7.8-19.06-7.8-7.8-19.2-7.8h-83v-83q0-11.4-7.8-19.2-7.8-7.8-19.06-7.8-11.25 0-19.7 7.8Q698-428.4 698-417v83h-83q-11.4 0-19.2 7.8-7.8 7.8-7.8 19.06 0 11.25 8.4 19.7Q604.8-279 616-279h82Zm25.5 159Q646-120 591-175.5T536-307q0-78.43 54.99-133.72Q645.98-496 724-496q77 0 132.5 55.28Q912-385.43 912-307q0 76-55.5 131.5t-133 55.5ZM135-269v-377q0-22.04 9.55-41.75Q154.09-707.47 172-721l251-189q13.3-9 27.92-13.5 14.63-4.5 29.36-4.5 14.72 0 29.13 4.5Q523.83-919 537-910l255 192q8 7.33 11.5 16.77t3.5 19.02q0 19.29-13.21 32.75Q780.57-636 761.04-636q-8.04 0-16.13-2T731-646L480-834 229-645.67V-269h207q20.1 0 33.05 13.68Q482-241.65 482-221.82q0 19.82-12.95 33.32T436-175H229q-39.8 0-66.9-27.1Q135-229.2 135-269Zm345-283Z"/></svg>
                    <h1>New Property</h1>
            </div>
            <div class="col-2 center-heading">
                <button type="submit" class="btn add">
                    Submit for Review
                </button>
            </div>
        </div>
       </div>

      <!-- Property Row Container -->
       <div class="container">
        <div class="row mb-5">
            <!-- Row 1 -->
             <div class="col-4  mb-5">
                <div class="box1 mb-4">
                    <h3>Property Details</h3>
                    
                        <div class="my-3">
                            <label for="title" class="form-label">Property Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Property Description</label>
                            <input type="text" name="description" class="form-control" id="description">
                        </div>
                        <div class="mb-3">
                            <label for="StreetAddress" class="form-label">Street Address</label>
                            <input type="text" name="streetAddress" class="form-control" id="StreetAddress">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" class="form-control" id="city">
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" name="state" class="form-control" id="state">
                        </div>
                        <div class="mb-3">
                            <label for="postalCode" class="form-label">Postal Code</label>
                            <input type="text" name="postalCode" class="form-control" id="postalCode">
                        </div>
                        <div class="mb-3">
                            <label for="propType" class="form-label">Property Type</label>
                            <select class="form-select" id="propType" name="propType">
                              <option selected>Choose...</option>
                              <option value="1">Treehouse</option>
                              <option value="2">Flower-den</option>
                              <option value="3">Mountainside Cottage</option>
                              <option value="4">Underwater Cove</option>
                              <option value="5">Mushroom House</option>
                            </select>
                          </div>
                        <div class="mb-3">
                            <label for="availableDate" class="form-label">Available From</label>
                            <input type="date" name="availableDate" class="form-control" id="availableDate">
                        </div>
                    
                </div>
                <div class="box1">
                    <h3 class="mb-4">Pricing</h3>
                    <div class="mb-3">
                            <select class="form-select" name="forSaleRent" id="forSaleRent">
                              <option selected>Choose...</option>
                              <option value="1">For Sale</option>
                              <option value="1">To Rent</option>
                            </select>
                          </div>
                    <div class="mb-3">
                        <input style="background-color: #A99DCF;" type="number" class="form-control" name="pricePm" id="pricePm">
                    </div>
                </div>
             </div>

             <!-- Row 2 -->
             <div class="col-4">
                <div class="box1 mb-4">
                    <h3>Interior</h3>
                        <div class="my-3">
                            <label for="totFloor" class="form-label">Total Floors</label>
                            <input type="number" name="totFloor" class="form-control" id="totFloor" aria-describedby="nameHelp">
                        </div>
                        <div class="mb-3">
                            <label for="floorSize" class="form-label">Floor Size</label>
                            <input type="number" name="floorSize" class="form-control" id="floorSize">
                        </div>
                        <div class="my-1">
                            <label for="totFloor" class="form-label">Rooms</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="bedrooms" class="number-input" id="bedrooms">
                            <label for="bedrooms" class="form-label mx-2">Bedrooms</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="bathrooms" class="number-input" id="bathrooms">
                            <label for="bathrooms" class="form-label mx-2">Bathrooms</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="kitchens" class="number-input" id="kitchens">
                            <label for="kitchens" class="form-label mx-2">Kitchens</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="diningRooms" class="number-input" id="diningRooms">
                            <label for="diningRooms" class="form-label mx-2">Dining Rooms</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="basements" class="number-input" id="basements">
                            <label for="basements" class="form-label mx-2">Basements</label>
                        </div>
                </div>
                <div class="box1">
                    <h3>Exterior</h3>
                        <div class="my-3">
                            <label for="lotSize" class="form-label">Lot Size</label>
                            <input type="number" name="lotSize" class="form-control" id="lotSize" aria-describedby="nameHelp">
                        </div>
                        <div class="my-1">
                            <label for="nameSurname" class="form-label">Exterior Spaces</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="parkingSpace" class="number-input" id="parkingSpace">
                            <label for="parking" class="form-label mx-2">Parking Spaces</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="gardens" class="number-input" id="gardens">
                            <label for="gardens" class="form-label mx-2">Gardens</label>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="patio" class="number-input" id="patio">
                            <label for="patio" class="form-label mx-2">Patio / Deck</label>
                        </div>
                </div>
             </div>

             <!-- Row 3 -->
             <div class="col-4 mb-5">
                <div class="box1 mb-4">
                    <h3>Features</h3>

                    <!-- Interior Checkboxes -->
                    <div class="my-1">
                        <label for="nameSurname" class="form-label">Interior</label>
                    </div>
                    <div class="checkbox">
                    <input id="wifi" type="checkbox" name="wifi" value="1">
                    <label class="check mb-4" for="wifi"><div class="check-label">Wifi / Internet</div></label>
                    <br>
                    <input id="airConditioning" type="checkbox" name="airConditioning" value="1">
                    <label class="check mb-4" for="airConditioning"><div class="check-label">Air Conditioning</div></label>
                    <br>
                    <input id="floorHeating" type="checkbox" name="floorHeating" value="1">
                    <label class="check mb-4" for="floorHeating"><div class="check-label">Floor Heating</div></label>
                    </div>

                    <!-- Exterior Checkboxes -->
                    
                        <div class="my-1">
                            <label for="nameSurname" class="form-label">Exterior Spaces</label>
                        </div>
                        <div class="checkbox">
                            <input id="pool" type="checkbox" name="pool" value="1">
                            <label class="check mb-4" for="pool"><div class="check-label">Pool Access</div></label>
                            <br>
                            <input id="fitnessCentr" type="checkbox" name="fitnessCentr" value="1">
                            <label class="check mb-4" for="fitnessCentr"><div class="check-label">Fitness Center</div></label>
                            <br>
                            <input id="gardenServ" type="checkbox" name="gardenServ" value="1">
                            <label class="check mb-4" for="gardenServ"><div class="check-label">Garden Services</div></label>
                            <br>
                            <input id="undercPark" type="checkbox" name="undercPark" value="1">
                            <label class="check mb-4" for="undercPark"><div class="check-label">Undercover Parking</div></label>
                            <br>
                            <input id="gatedCom" type="checkbox" name="gatedCom" value="1">
                            <label class="check mb-4" for="gatedCom"><div class="check-label">Gated Community</div></label>
                        </div>
                    
                </div>
                <div class="box1">
                    <h3>Imagery</h3>
                    <div class="image-uploader" id="image-uploader"></div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Property Images</label>
                        <input type="file" name="images" class="form-control" id="images">
                    </div>

                </div>
             </div>
             </form>
        </div>
       </div>

       <!-- Footer -->
 <?php include '../components/footer.php'; ?>

 
</body>
</html>