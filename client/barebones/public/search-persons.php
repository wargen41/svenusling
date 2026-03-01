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
        // Search persons
        $persons = searchPersons($baseUrl, $query);
        $persons_count = $persons['pagination']['total'];

        echo "<h1>Sökresultat</h1>";

        echo "<details open>";
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
