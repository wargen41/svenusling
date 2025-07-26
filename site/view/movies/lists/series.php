<?php

$viewType = 'movies';

echo pgHeadingHTML($viewType, getStr('SERIES_LIST_TITLE'));

$query = implode(' ', [
    "SELECT Title, Year, Rating, MovieID",
    "FROM movies",
    "WHERE Hidden IS NOT 1 AND (Type == 'series' OR Type == 'season' OR Type == 'episode' OR Type == 'miniseries')",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

include $GLOBALS['my_dir']."view/list-url_query.php";

?>
