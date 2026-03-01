<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

if(isset($_GET) && isset($_GET['query'])){
    $query = $_GET['query'] ?? null;
}
$prefilled = $query ?? "";

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($query)){
        // Search movies
        $movies = searchMovies($baseUrl, $query);
        $movies_count = $movies['pagination']['total'];

        echo "<h1>Sökresultat</h1>";

        echo "<details open>";
        echo "<summary>Hittade $movies_count filmer</summary>";
        echo "<ul>";
        foreach ($movies['data'] as $item) {
            $id = $item['id'];
            $title = $item['title'];
            echo "<li><a href=\"movie.php?id=$id\">$title</a></li>";
        }
        echo "</ul>";
        echo "</details>";
    }else{

    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
