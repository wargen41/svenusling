<h1><?php echo getStr('FILMS_AND_SERIES_LIST_TITLE'); ?></h1>

<ol class="list movies">

<?php
$query = implode(' ', [
    "SELECT Title, Year, Rating",
    "FROM movies",
    "WHERE Hidden IS NOT 1",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

$listType = 'list';
$listStyle = 'simple';
include $GLOBALS['my_dir']."view/movies/item-styles/{$listType}-{$listStyle}.php";

?>

</ol>
