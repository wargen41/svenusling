<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($query)){
        // Search movies
        $movies = searchMovies($baseUrl, $query);
        $movies_count = $movies['pagination']['total'];
        // Search persons
        $persons = searchPersons($baseUrl, $query);
        $persons_count = $persons['pagination']['total'];

        $movies_open = " open";
        if($movies_count > 10){
            $movies_open = "";
        }
        $persons_open = " open";
        if($persons_count > 10){
            $persons_open = "";
        }

        echo "<h1>Sökresultat</h1>";

        echo "<details$movies_open>";
        echo "<summary>Hittade $movies_count filmer</summary>";
        echo "<ul>";
        foreach ($movies['data'] as $item) {
            $id = $item['id'];
            $title = $item['title'];
            $year = $item['year'] ?? null;
            $year_str = "";
            if($year){
                $year_str = " ($year)";
            }
            echo "<li><a href=\"movie.php?id=$id\">$title$year_str</a></li>";
        }
        echo "</ul>";
        echo "</details>";

        echo "<details$persons_open>";
        echo "<summary>Hittade $persons_count personer</summary>";
        echo "<ul>";
        foreach ($persons['data'] as $item) {
            $id = $item['id'];
            $name = $item['name'];
            echo "<li><a href=\"person.php?id=$id\">$name</a></li>";
        }
        echo "</ul>";
        echo "</details>";
    }else{

    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
