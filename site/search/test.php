<?php

require __DIR__.'/../includes/collections/default.php';

$endpoint = $GLOBALS['base_uri'].'/search/movies/genres';
$test = dbSearch($endpoint, null);

print_rPRE($test);

?>
