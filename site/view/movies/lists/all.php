<h1><?php echo getStr('FILMS_AND_SERIES_LIST_TITLE'); ?></h1>

<?php

$query = implode(' ', [
    "SELECT Title, Year, Rating, MovieID",
    "FROM movies",
    "WHERE Hidden IS NOT 1",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

$viewType = 'movies';
include $GLOBALS['my_dir']."view/list-url_query.php";

?>
