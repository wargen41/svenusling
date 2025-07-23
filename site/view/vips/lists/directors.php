<h1><?php echo getStr('DIRECTORS_LIST_TITLE'); ?></h1>

<?php

$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "WHERE Category = 'director'",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

$viewType = 'vips';
include $GLOBALS['my_dir']."view/list-url_query.php";

?>
