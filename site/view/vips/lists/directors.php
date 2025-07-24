<?php

$viewType = 'vips';

echo pgHeadingHTML(getStr('DIRECTORS_LIST_TITLE'), $viewType);

$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "WHERE Category = 'director'",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

include $GLOBALS['my_dir']."view/list-url_query.php";

?>
