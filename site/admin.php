<?php
session_start();
require 'includes/includes-admin.php';

$page_title = 'Admin';

require 'templates/html-start.php';
include 'templates/header.php';
include 'admin/admin-main.php';
include 'templates/footer.php';
require 'templates/html-end.php';

closeDB();

?>
