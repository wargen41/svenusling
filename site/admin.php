<?php
session_start();
require 'includes/includes-admin.php';

$page_title = 'Admin';

require 'templates/html-start.php';

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
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
