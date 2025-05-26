<?php
session_start();
session_unset();
session_destroy();
require_once 'includes/db_config.php';
header("Location: " . BASE_URL . "index.php");
exit();
?>