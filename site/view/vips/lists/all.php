<?php

$viewType = 'vips';

echo pgHeadingHTML($viewType, getStr('VIPS_LIST_TITLE'));

$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

include $GLOBALS['my_dir']."view/list-url_query.php";

?>

</ol>
