<?php
session_start();
require __DIR__.'/../../includes/includes-default.php';

$page_title = getStr('RATED_MOVIES_TITLE');

require $GLOBALS['my_dir'].'templates/html-start.php';
include $GLOBALS['my_dir'].'templates/header.php';
include $GLOBALS['my_dir'].'templates/site-widget.php';
?>

<main id="main-content">

<?php

if(isset($_GET) && isset($_GET['list'])){
    $listToDisplay = sanitizeQuery($_GET['list']);
    include $GLOBALS['my_dir'].'view/movies/'.$listToDisplay.'.php';
}else{
    include $GLOBALS['my_dir'].'view/movies/intro.php';
}

?>

</main>

<?php

include $GLOBALS['my_dir'].'templates/footer.php';

include $GLOBALS['my_dir'].'includes/admin-tools.php';
require $GLOBALS['my_dir'].'templates/html-end.php';

closeDB();

?>
