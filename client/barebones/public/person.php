<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        // Get single person
        $person = getPerson($baseUrl, $id);
        $data = $person['data'];

        $name = $data['name'];
        $category = $data['category'];

        echo "<h1>$name ($category)</h1>";

        echo print_rPRE($person['data']);

    }else{
        echo "Inget id angett";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
