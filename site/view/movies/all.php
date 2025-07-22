<h1><?php echo getStr('MOVIES_LIST_TITLE'); ?></h1>

<?php
$query = implode(' ', [
    "SELECT m.Title, m.Year, m.Rating, media.FileName, media.Directory",
    "FROM movies m",
    "JOIN media_movies mm ON m.MovieID = mm.MovieID",
    "JOIN media ON mm.MediaID = media.MediaID",
    "WHERE mm.MediaID = m.PosterImageID",
    "AND m.Hidden IS NOT 1 AND (m.Type = 'film' OR m.Type = 'short')",
    "ORDER BY Sorting",
]);
$res = $GLOBALS['db']->query($query);

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $title = htmlSafeOutput($row['Title']);
    $year = htmlSafeOutput($row['Year']);
    $rating = htmlSafeOutput($row['Rating']);
    $imgSrc = urlSafeOutput($row['Directory'].$row['FileName']);

    $imgHTML = '' /*'<img alt="['.$title.' poster]" src="'.$imgSrc.'"> '*/;
    echo htmlWrap('li', "{$imgHTML} {$title} ({$year}) - {$rating}");
}

?>
