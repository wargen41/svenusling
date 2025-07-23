<h1><?php echo getStr('ACTORS_LIST_TITLE'); ?></h1>

<?php

$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "WHERE Category = 'actor' OR Category = 'voice_actor'",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

$viewType = 'vips';
include $GLOBALS['my_dir']."view/list-url_query.php";

?>
