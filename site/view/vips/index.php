<?php
session_start();
require __DIR__.'/../../includes/collections/default.php';

$page_title = getStr('VIPS_TITLE');

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<?php

if(isset($_GET) && isset($_GET['list'])){
    $listToDisplay = sanitizeQuery($_GET['list']);
    include $GLOBALS['my_dir'].'view/vips/lists/'.$listToDisplay.'.php';
}else{
    include $GLOBALS['my_dir'].'view/vips/intro.php';
}

?>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
