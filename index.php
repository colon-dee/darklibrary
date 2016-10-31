<?php
define('INCL_FILE', 'true');
session_start();

// Verify if user is logged in.
if (empty($_SESSION['user']['loggedin']) || !$_SESSION['user']['loggedin']) {
    // Import login page
    $def_navbar = false;
    require_once "construct.php";
    require_once "account/login.php";
    exit();
} else {
    require_once "construct.php";
}

echo "Hi :D";
?>
