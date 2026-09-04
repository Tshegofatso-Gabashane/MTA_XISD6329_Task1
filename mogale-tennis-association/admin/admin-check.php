<?php

session_start();


// Check whether administrator is logged in

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");

    exit;

}

?>