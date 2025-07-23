<?php

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    include $GLOBALS['my_dir'].'includes/templates/admin-widget.php';
}

?>
