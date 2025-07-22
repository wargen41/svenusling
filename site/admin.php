<?php
session_start();
require 'includes/includes-admin.php';

$isLoggedIn = (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true);

if($isLoggedIn){
    $page_title = 'Admin';
}else{
    $page_title = 'Logga in';
}

require 'templates/html-start.php';

if($isLoggedIn) {
    include 'templates/header.php';
    include 'templates/site-widget.php';

    include 'admin/admin-main.php';

    include 'templates/footer.php';
}
else{
    include 'admin/admin-login.php';
}

require 'templates/html-end.php';

closeDB();

?>
