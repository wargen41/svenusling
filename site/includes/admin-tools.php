<?php

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    include $GLOBALS['my_dir'].'templates/admin-widget.php';
}

?>
