<?php
// logout.php
session_start();
session_unset();    // Unset all session variables
session_destroy();  // Destroy the session
require_once 'includes/db_config.php'; // To use BASE_URL constant
header("Location: " . BASE_URL . "index.php"); // Redirect to homepage or login page
exit();
?>