<?php
session_start();
require 'includes/includes-admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form = $_POST['form'];

    include 'admin/'.$form.'-update.php';

} else {
    echo "Invalid request.";
}

closeDB();

?>
