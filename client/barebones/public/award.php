<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all awards
    $awards = getAwards($baseUrl);

    echo "<h1>Alla utmärkelser</h1>";
    echo "<p>Antal: " . $awards['count'] . "</p>";

    echo "<ul>";
    foreach ($awards['data'] as $item) {
        $award = $item['award'];
        echo "<li>$award</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
