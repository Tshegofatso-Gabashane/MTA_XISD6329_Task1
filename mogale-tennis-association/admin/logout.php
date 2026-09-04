<?php

session_start();


// Clear admin session

unset($_SESSION["admin_id"]);
unset($_SESSION["admin_name"]);
unset($_SESSION["admin_email"]);


// Destroy session

session_destroy();


// Return to admin login

header("Location: login.php");

exit;

?>