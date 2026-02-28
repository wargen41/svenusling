<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        // Get single movie
        $movie = getMovie($baseUrl, $id);
        $data = $movie['data'];

        $title = $data['title'];
        $type = $data['type'];
        $rating = suRating($data['rating']);

        echo "<h1>$title ($type)</h1>";
        echo "<p>$rating</p>";

        echo print_rPRE($movie['data']);

    }else{
        echo "Inget id angett";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
