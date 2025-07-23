<h1><?php echo getStr('SERIES_LIST_TITLE'); ?></h1>

<ol class="list movies series">

<?php

$query = implode(' ', [
    "SELECT Title, Year, Rating",
    "FROM movies",
    "WHERE Hidden IS NOT 1 AND (Type == 'series' OR Type == 'season' OR Type == 'episode' OR Type == 'miniseries')",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

$listType = 'list';
$listStyle = 'simple';
include $GLOBALS['my_dir']."view/movies/item-styles/{$listType}-{$listStyle}.php";

?>

</ol>
