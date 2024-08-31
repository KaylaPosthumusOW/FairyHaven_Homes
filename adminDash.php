<?php
session_start(); // Start the session
require 'config.php'; // Include your database configuration

// Check if the session variable 'email' is set
if (!isset($_SESSION['email'])) {
    header("Location: pages/login.php"); // Redirect to login page if not logged in
    exit(); // Terminate the script to ensure redirection
}

// Retrieve session variables
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';

// Handle form submission for approving/rejecting listings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['listing_id'])) {
    $listingId = $_POST['listing_id'];
    $newStatus = $_POST['new_status']; // Should be 'approved' or 'rejected'

    // Update the status of the listing
    $sqlUpdate = "UPDATE listing SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("si", $newStatus, $listingId);

    if ($stmt->execute()) {
        echo "Listing status updated successfully.";
        header("Location: admindash.php"); // Refresh the page to reflect changes
        exit();
    } else {
        echo "Error updating listing status: " . $conn->error;
    }
}

// Query to fetch pending listings
$sql = "SELECT * FROM listing WHERE status = 'pending'";
$result = $conn->query($sql);
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
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation -->
            <div class="col-2">
                <nav class="admin-nav">
                    <img class="admin-logo" src="assets/FHH-Logo_white.png" alt="FairyHaven Homes Logo">
                    <ul>
                        <li>Home</li>
                        <li><strong>Pending Listings</strong></li>
                        <li>Flagged Comments</li>
                    </ul>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-10 mt-5">
                <h1 class="mb-4">Pending Property Listings</h1>

                <!-- Listings -->
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="static-info mb-3">
                    <div class="row">
                        <div class="col-10">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><small><?php echo htmlspecialchars($row['propType']); ?></small></p>
                            <p><?php echo htmlspecialchars($row['streetAddress']); ?></p>
                        </div>
                        <div class="col-2">
                            <button class="learn-more btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo-<?php echo $row['id']; ?>" aria-expanded="false" aria-controls="collapseInfo-<?php echo $row['id']; ?>">
                                Learn More v
                            </button> 
                        </div>
                    </div>
                </div>

                <!-- Dropdown Info Card (Initially Hidden) -->
                <div class="collapse mt-3" id="collapseInfo-<?php echo $row['id']; ?>">
                    <div class="static-info mb-3">
                        <div class="row">
                            <div class="col-9">
                                <!-- Property Details -->
                                <div class="row">
                                    <div class="col-3 mb-3">
                                        <img class="pending-img" src="uploads//<?php echo htmlspecialchars($row['images']); ?>" alt="Property Image">
                                    </div>
                                    <div class="col-9">
                                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                        <p><small><?php echo htmlspecialchars($row['propType']); ?></small></p>
                                        <p><strong><?php echo htmlspecialchars($row['streetAddress']); ?></strong></p>
                                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                                    </div>
                                </div>
                                <!-- Exterior + Interior -->
                                <div class="row">
                                    <div class="col-6">
                                        <!-- Exterior Box -->
                                        <div class="box2 mb-3">
                                            <h3>Exterior</h3>
                                            <div class="my-1">
                                                <label for="lotSize" class="form-label mb-3">Lot Size</label>
                                                <div class="text-bg">
                                                    <p><?php echo htmlspecialchars($row['lotSize']); ?></p>
                                                </div>
                                            </div>
                                            <div class="my-1">
                                                <label for="exteriorSpaces" class="form-label">Exterior Spaces</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['parkingSpace']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Parking Spaces</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['gardens']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Gardens</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['patio']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Patio / Deck</label>
                                            </div>
                                        </div>
                                        <!-- Pricing -->
                                        <div class="box">
                                            <h3 class="mb-3">Pricing</h3>
                                            <div class="forSaleRent mb-2"><p><?php echo htmlspecialchars($row['forSaleRent']); ?></p></div>
                                            <div class="price"><p><?php echo htmlspecialchars($row['pricePm']); ?></p></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <!-- Interior Box -->
                                        <div class="box2 mb-3">
                                            <h3>Interior</h3>
                                            <div class="my-1">
                                                <label for="totalFloors" class="form-label mb-3">Total Floors</label>
                                                <div class="text-bg">
                                                    <p><?php echo htmlspecialchars($row['totFloor']); ?></p>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="floorSize" class="form-label mb-3">Floor Size</label>
                                                <div class="text-bg">
                                                    <p><?php echo htmlspecialchars($row['floorSize']); ?></p>
                                                </div>
                                            </div>
                                            <div class="my-1">
                                                <label for="rooms" class="form-label">Rooms</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['bedrooms']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Bedrooms</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['bathrooms']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Bathrooms</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['kitchens']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Kitchens</label>
                                            </div>
                                            <div class="align mb-2">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['diningRooms']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Dining Rooms</label>
                                            </div>
                                            <div class="align">
                                                <div class="number-input"><p><?php echo htmlspecialchars($row['basements']); ?></p></div>
                                                <label class="form-label mx-2 mb-1">Basements</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 features">
                                <h3>Features</h3>
                                <form method="POST" action="">
                                <input type="hidden" name="listing_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="new_status" value="approved" class="approve-btn">Approve</button>
                                <button type="submit" name="new_status" value="rejected" class="decline-btn">Reject</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.learn-more').forEach(button => {
            button.addEventListener('click', function() {
                if (this.textContent.trim() === 'Learn More v') {
                    this.textContent = 'Show Less ^';
                } else {
                    this.textContent = 'Learn More v';
                }
            });
        });
    </script>
</body>
</html>
