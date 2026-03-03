<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    // Get all relations
    $relations = getRelations($baseUrl);
    
    $rel_li_html = [];
    foreach($relations['data'] as $rel){
        $id = $rel['id'];
        $text = $rel['sv'];
        $rel_li_html[] = "<li><a href=\"relations.php?id=$id\">$text</a></li>";
    }

    echo "<h1>Sven Usling databaskoll</h1>";
    echo "<ul>";
    echo "<li><a href=\"movies.php\">Alla inlagda i filmlistan</a>";
    echo "  <ul>";
    echo "  <li><a href=\"films.php\">Alla filmer</a></li>";
    echo "  <li><a href=\"series.php\">Alla serier</a></li>";
    echo "  <li><a href=\"miniseries.php\">Alla miniserier</a></li>";
    echo "  </ul>";
    echo "</li>";
    echo "<li><a href=\"persons.php\">Alla viktiga personer</a></li>";
    echo "  <ul>";
    echo "  <li><a href=\"directors.php\">Alla regissörer</a></li>";
    echo "  <li><a href=\"actors.php\">Alla skådespelare</a></li>";
    echo "  </ul>";
    echo "</li>";
    echo "<li><a href=\"relations.php\">Alla relationer</a></li>";
    echo "  <ul>";
    echo implode("  ", $rel_li_html);
    echo "  </ul>";
    echo "</li>";
    echo "<li><a href=\"awards-categories.php\">Alla utmärkelser och kategorier</a>";
    echo "  <ul>";
    echo "  <li><a href=\"awards.php\">Alla utmärkelser</a></li>";
    echo "  </ul>";
    echo "</li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
