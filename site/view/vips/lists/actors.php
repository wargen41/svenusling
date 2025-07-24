<?php

$viewType = 'vips';

echo pgHeadingHTML(getStr('ACTORS_LIST_TITLE'), $viewType);

$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "WHERE Category = 'actor' OR Category = 'voice_actor'",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

include $GLOBALS['my_dir']."view/list-url_query.php";

?>
