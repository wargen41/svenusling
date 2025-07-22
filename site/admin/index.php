<?php
session_start();
require __DIR__.'/../includes/includes-admin.php';
//require 'includes/includes-admin.php';

$isLoggedIn = (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true);

if($isLoggedIn){
    $page_title = 'Admin';
}else{
    $page_title = 'Logga in';
}

require $GLOBALS['my_dir'].'templates/html-start.php';

if($isLoggedIn) {
    include $GLOBALS['my_dir'].'templates/header.php';
    include $GLOBALS['my_dir'].'templates/site-widget.php';

    include $GLOBALS['my_dir'].'admin/admin-main.php';

    include $GLOBALS['my_dir'].'templates/footer.php';
}
else{
    include $GLOBALS['my_dir'].'admin/admin-login.php';
}

require $GLOBALS['my_dir'].'templates/html-end.php';

closeDB();

?>
