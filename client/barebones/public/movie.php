<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        // Get single movie
        $movie = getMovie($baseUrl, $id);
        $data = $movie['data'];

        $title = $data['title'];
        $original_title = $data['original_title'] ?? null;
        $type = $data['type'];
        $rating = $data['rating'] ?? null;
        $genres = $data['genres'];

        $persons = $data['persons'];
        $directors = $persons['director'] ?? null;
        $actors = $persons['actor'] ?? null;
        $voice_actors = $persons['voice_actor'] ?? null;

        echo "<h1>$title ($type)</h1>";
        if($original_title){
            echo "<p>$original_title</p>";
        }
        if($rating){
            $rating = suRating($rating);
            echo "<p>$rating</p>";
        }
        if(!empty($genres)){
            $sv_genres = [];
            foreach($genres as $genre){
                array_push($sv_genres, $genre['sv']);
            }
            echo "<p>";
            echo implode(', ', $sv_genres);
            echo "</p>";
        }
        if(!empty($directors)){
            echo "<h2>Regi</h2>";
            echo "<ul>";
            foreach($directors as $item){
                $id = $item['id'];
                $name = $item['name'];
                echo "<li><a href=\"person.php?id=$id\">$name</a></li>";
            }
            echo "</ul>";
        }
        if(!empty($actors)){
            echo "<h2>Skådespelare</h2>";
            echo "<ul>";
            foreach($actors as $item){
                $id = $item['id'];
                $name = $item['name'];
                echo "<li><a href=\"person.php?id=$id\">$name</a></li>";
            }
            echo "</ul>";
        }
        if(!empty($voice_actors)){
            echo "<h2>Röster</h2>";
            echo "<ul>";
            foreach($voice_actors as $item){
                $id = $item['id'];
                $name = $item['name'];
                echo "<li><a href=\"person.php?id=$id\">$name</a></li>";
            }
            echo "</ul>";
        }

        echo print_rPRE($movie['data']);

    }else{
        echo "Inget id angett";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
