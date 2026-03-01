<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all awards
    $categories = getAwardsCategories($baseUrl);

    echo "<h1>Alla utmärkelser och kategorier</h1>";
    echo "<p>Antal: " . $categories['count'] . "</p>";

    echo "<dl>";
    foreach ($categories['data'] as $item) {
        $id = $item['id'];
        $award = $item['award'];
        $category = $item['category'];
        if($category === ""){
            $category = "<em>Huvudkategori?</em>";
        }
        echo "<dt>$award</dt>";
        echo "<dd><a href=\"award.php?id=$id\">$category</a></dd>";
    }
    echo "</dl>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
