<section id="puff-rated-movies">
<h2><?php echo getStr('RATED_MOVIES_TITLE'); ?></h2>
<?php
$movieCount = dbCountAllRows('movies');
$movieCountStr = 'Typ '.$movieCount.' betygsatta filmer';
?>
<p>Typ <?php echo $movieCount; ?> betygsatta filmer så långt</p>

<p>Här är de senaste:</p>
<ul>
<?php
$query = implode(' ', [
    "SELECT m.Title, media.FileName, media.Directory",
    "FROM movies m",
    "JOIN media_movies mm ON m.MovieID = mm.MovieID",
    "JOIN media ON mm.MediaID = media.MediaID",
    "WHERE mm.MediaID = m.PosterImageID",
    "AND m.PublishedDate != '' AND m.Grade != ''",
    "ORDER BY PublishedDate DESC LIMIT 6",
]);
$res = $GLOBALS['db']->query($query);
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $imgPath = $row['Directory'].$row['FileName'];
    $imgHTML = '<img alt="['.$row['Title'].' poster]" src="'.$imgPath.'"> ';
    echo htmlWrap('li', $imgHTML.$row['Title']);
}
?>
</ul>
</section>
