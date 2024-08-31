<?php
require 'config.php';
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: /fairyhaven_homes/pages/logIn.php"); // Redirect to the login page
exit();
?>
