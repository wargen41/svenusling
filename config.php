<?php
// BASE URI (use one, comment out the other)
// If the site is in a sub directory, 
// write it with a starting slash, like this:
$GLOBALS['base_uri'] = "/svenusling-site";
// Otherwise leave this value blank, like this:
// $GLOBALS['base_uri'] = "";

// Path to the SQLite database file
// There is no need to put the database
// in a directory with web access
// Just make sure that the web server user
// has read/write access to the file as
// well as the parent directory
$GLOBALS['db_path'] = "/var/www/db/svenusling/";
$GLOBALS['db_name'] = "svenusling.db";
?>
