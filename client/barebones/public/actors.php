<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all actors
    $persons = getActors($baseUrl);

    echo "<h1>Alla skådespelare</h1>";
    echo "<p>Antal: " . $persons['pagination']['total'] . "</p>";

    echo "<ul>";
    foreach ($persons['data'] as $item) {
        $id = $item['id'];
        $name = $item['name'];
        echo "<li><a href=\"person.php?id=$id\">$name</a></li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
