<?php
session_start();
require 'includes/includes-default.php';

openDB();

$page_title = getStr('START_TITLE');

require 'templates/html-start.php';
include 'templates/header.php';
include 'templates/site-widget.php';

include 'templates/index-main.php';

include 'templates/footer.php';

include 'includes/admin-tools.php';
require 'templates/html-end.php';

closeDB();

?>
