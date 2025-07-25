<?php
session_start();
require 'includes/collections/default.php';

$page_title = getStr('START_TITLE');

require 'includes/snippets/html-start.php';
include 'includes/templates/header.php';
include 'includes/templates/site-widget.php';

include 'index-main.php';

include 'includes/templates/footer.php';

include 'includes/snippets/admin-tools.php';
require 'includes/snippets/html-end.php';

closeDB();

?>
