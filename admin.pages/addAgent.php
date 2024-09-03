<?php
session_start(); // Start the session
require '../config.php'; // Include your database configuration

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input values
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $number = $_POST['number'];
    $email = $_POST['email'];

    // Insert the new agent into the database
    $sqlInsertAgent = "INSERT INTO Agent (firstName, lastName, number, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsertAgent);
    $stmt->bind_param("ssss", $firstName, $lastName, $number, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Agent added succesfully');</script>";
    } else {
        echo "<div class='alert alert-danger'>Error adding agent: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// Fetch agents from the database
$sqlFetchAgents = "SELECT * FROM Agent";
$resultAgents = $conn->query($sqlFetchAgents);

// Fetch listings from the database
$sqlFetchListings = "SELECT * FROM Listing";
$resultListings = $conn->query($sqlFetchListings);

// Organize listings by agent
$listingsByAgent = [];
if ($resultListings->num_rows > 0) {
    while ($listing = $resultListings->fetch_assoc()) {
        $agentId = $listing['agentId'];
        if (!isset($listingsByAgent[$agentId])) {
            $listingsByAgent[$agentId] = [];
        }
        $listingsByAgent[$agentId][] = $listing;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Agents</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="../styles/adminDash.css">
    <link rel="stylesheet" href="../styles/globalStyles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation -->
            <div class="col-2">
                <nav class="admin-nav">
                    <img class="admin-logo" src="../assets/FHH-Logo_white.png" alt="FairyHaven Homes Logo">
                    <ul>
                        <li>
                            <a class="admin-link" href="<?php echo '../adminDash.php'; ?>">Profile</a>
                        </li>
                        <li>
                            <a class="admin-link" href="<?php echo 'pendingListing.php'; ?>">Pending Listings</a>
                        </li>
                        <li>
                            <a class="admin-link" href="<?php echo 'addAgent.php'; ?>"><strong>Agents</strong></a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-10 mt-5">
                <div class="row">
                    <div class="col-10">
                        <h1 class="mb-4">FairyHaven Homes Agents</h1>
                    </div>
                </div>

                <!-- Agents -->
                <div class="row">
                    <div class="col-8">
                        <!-- Agents Info -->
                        <?php if ($resultAgents->num_rows > 0): ?>
                            <?php while ($agent = $resultAgents->fetch_assoc()): ?>
                                <div class="agents-info mb-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h3 class="mb-3"><?php echo htmlspecialchars($agent['firstName'] . ' ' . $agent['lastName']); ?></h3>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p>
                                                        <small>
                                                            <i class="fas fa-phone me-2"></i> <!-- Font Awesome phone icon -->
                                                            <?php echo htmlspecialchars($agent['number']); ?>
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="col-5">
                                                    <p>
                                                        <small>
                                                            <i class="fas fa-envelope me-2"></i> <!-- Font Awesome email icon -->
                                                            <?php echo htmlspecialchars($agent['email']); ?>
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            
                                            <!-- Display the listings associated with the agent -->
                                            <h4 class="mb-3">Listings</h4>
                                            <?php
                                            $agentId = $agent['AgentId'];
                                            if (isset($listingsByAgent[$agentId])):
                                                foreach ($listingsByAgent[$agentId] as $listing): ?>
                                                    <p><?php echo htmlspecialchars($listing['title']); ?></p>
                                                <?php endforeach; 
                                            else: ?>
                                                <p>No listings available for this agent.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No agents found in the database.</p>
                        <?php endif; ?>
                    </div>

                    <div class="addAgent_form col-3">
                        <!-- Form for inserting a new agent -->
                        <h3 class=" mb-3">Add New Agent</h3>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                            <div class="mb-3">
                                <label for="number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="number" name="number" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn addAgent_btn">Add Agent</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
