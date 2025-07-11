<?php

$db_path = "/var/www/db/svenusling/";
$GLOBALS['db'] = new SQLite3($db_path . 'svenusling.db');

function getSiteVars() {
    $res = $GLOBALS['db']->query('SELECT * FROM site');

    $vars = [];
    while ($row = $res->fetchArray()) {
        $vars[$row['var']] = $row['value'];
    }

    return $vars;
}

function getTexts($category, $lang) {
    $query = "SELECT id, " . $lang . " FROM site_text WHERE category='" . $category . "'";
    $res = $GLOBALS['db']->query($query);

    $texts = [];
    while ($row = $res->fetchArray()) {
        $texts[$row['id']] = $row[$lang];
    }
    return $texts;
}

?>
