<?php
session_start();
require __DIR__.'/../../includes/includes-admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form = $_POST['form'];

    include $GLOBALS['my_dir'].'admin/insert/'.$form.'.php';

} else {
    echo $GLOBALS['BAD_REQUEST_TEXT'];
}

// Detta körs aldrig när man blir omdirigerad av
// respektive uppdateringsskript
// Hur löser vi det?
closeDB();

?>
