<?php
session_start();
require __DIR__.'/../../includes/collections/default.php';

$page_title = getStr('VIDEO_REVIEWS_TITLE');

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<p>Här ska vi lista de länkar till Youtube som lagts in i databasen.</p>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
