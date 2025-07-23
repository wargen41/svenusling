<h1><?php echo getStr('MOVIES_AND_SERIES_LIST_TITLE'); ?></h1>

<ol class="list movies">

<?php
$query = implode(' ', [
    "SELECT Title, Year, Rating",
    "FROM movies",
    "WHERE Hidden IS NOT 1",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $title = htmlSafeOutput($row['Title']);
    $year = htmlSafeOutput($row['Year']);
    $rating = htmlSafeOutput($row['Rating']);

    echo htmlWrap('li', "{$title} ({$year}) - {$rating}");
}

?>

</ol>
