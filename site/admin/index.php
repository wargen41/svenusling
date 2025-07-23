<?php
session_start();
require __DIR__.'/../includes/collections/admin.php';
openDB();

$isLoggedIn = (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true);

if($isLoggedIn){
    $page_title = 'Admin';
}else{
    $page_title = 'Logga in';
}

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';

if($isLoggedIn) {
    include $GLOBALS['my_dir'].'includes/templates/header.php';
    include $GLOBALS['my_dir'].'includes/templates/site-widget.php';

    include $GLOBALS['my_dir'].'admin/admin-main.php';

    include $GLOBALS['my_dir'].'includes/templates/footer.php';
}
else{
    include $GLOBALS['my_dir'].'admin/admin-login.php';
}

require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
