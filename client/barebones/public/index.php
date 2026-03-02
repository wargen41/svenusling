<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all movies
    $movies = getMovies($baseUrl);

    echo "<h1>Sven Usling databaskoll</h1>";
    echo "<ul>";
    echo "<li><a href=\"movies.php\">Alla inlagda i filmlistan</a>";
    echo "  <ul>";
    echo "  <li><a href=\"films.php\">Alla filmer</a></li>";
    echo "  <li><a href=\"series.php\">Alla serier</a></li>";
    echo "  <li><a href=\"miniseries.php\">Alla miniserier</a></li>";
    echo "  </ul>";
    echo "</li>";
    echo "<li><a href=\"persons.php\">Alla viktiga personer</a></li>";
    echo "<li><a href=\"awards.php\">Alla utmärkelser</a></li>";
    echo "<li><a href=\"awards-categories.php\">Alla utmärkelser och kategorier</a></li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
