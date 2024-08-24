<?php 
$servername = "localhost"; // The address of the database server, usually "localhost" for local development
$username = "root"; // The username used to connect to the database, "root" is common for local development
$password = ""; // The password for the database user, often empty for local development
$dbname = "fairyhaven_homes"; // The name of the database you want to connect to

// Create a new connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If there is a connection error, stop the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}

// If the connection is successful, the script continues running
?>
