<?php

$viewType = 'movies';

echo pgHeadingHTML($viewType, getStr('FILMS_AND_SERIES_LIST_TITLE'));

$query = implode(' ', [
    "SELECT Title, Year, Rating, MovieID",
    "FROM movies",
    "WHERE Hidden IS NOT 1",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

include $GLOBALS['my_dir']."view/list-url_query.php";

?>
