<?php
session_start();
require 'includes/include-default.php';

$page_title = 'Admin';

require 'templates/html-start.php';
include 'templates/header.php';
include 'templates/admin-main.php';
include 'templates/footer.php';
require 'templates/html-end.php';

?>
