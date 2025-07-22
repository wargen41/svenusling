<?php
session_start();
require 'includes/required.php';

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
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
else{
    header("Location: {$GLOBALS['base_uri']}/");
    exit;
}

?>
