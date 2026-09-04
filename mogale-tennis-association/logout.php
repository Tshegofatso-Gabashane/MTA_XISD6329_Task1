<?php

session_start();


// Remove all session variables

$_SESSION = [];


// Destroy session

session_destroy();


// Return to login page

header("Location: login.php");

exit;

?>