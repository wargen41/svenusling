<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all voice actors
    $persons = getVoiceActors($baseUrl);

    echo mainHeading("Alla röstskådespelare");
    echo "<p>Antal: " . $persons['pagination']['total'] . "</p>";

    echo "<ul class=\"long list\">";
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
