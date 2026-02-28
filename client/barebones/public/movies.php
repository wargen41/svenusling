<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all movies
    $movies = getMovies($baseUrl);

    echo "<h1>Alla inlagda filmer</h1>";
    echo "<p>Antal: " . $movies['pagination']['total'] . "</p>";

    echo "<ul>";
    foreach ($movies['data'] as $movie) {
        $id = $movie['id'];
        $title = $movie['title'];
        echo "<a href=\"movie.php?id=$id\"><li>$title</li></a>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
