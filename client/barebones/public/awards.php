<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all awards
    $awards = getAwards($baseUrl);

    echo mainHeading("Alla utmärkelser");
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
