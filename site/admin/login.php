<?php
session_start();
require __DIR__.'/../includes/collections/login.php';

function passwordIsCorrect($password) {
    if($password != ''){
        return true;
    }
    return false;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check the password and set session variables
    if(passwordIsCorrect($_POST['password'])){
        $_SESSION["loggedIn"] = true;
    }
    $location = $_SERVER['HTTP_REFERER'] ?? $GLOBALS['base_uri'].'/admin/';
    header("Location: {$location}");
    exit;
}
else{
    header("Location: {$GLOBALS['base_uri']}/");
    exit;
}

?>
