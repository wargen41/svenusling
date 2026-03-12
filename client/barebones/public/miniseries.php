<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all movies
    $movies = getMiniseriesList($baseUrl);

    echo "<h1>Alla inlagda miniserier</h1>";
    echo "<p>Antal: " . $movies['pagination']['total'] . "</p>";

    echo "<ul class=\"long list\">";
    foreach ($movies['data'] as $item) {
        $id = $item['id'];
        $title = $item['title'];
        $rating = $item['rating'] ?? null;
        $year = $item['year'] ?? null;
        $year_str = "";
        if($year){
            $year_str = " ($year)";
        }
        $rating_str = "";
        if($rating){
            $rating_str = ' '.suRating($rating);
        }
        echo "<li><a href=\"movie.php?id=$id\">$title$year_str$rating_str</a></li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
