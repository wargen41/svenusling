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

function getTextLanguages() {
    $res = $GLOBALS['db']->query("PRAGMA table_info(site_text)");

    $lang = [];
    while ($row = $res->fetchArray(SQLITE3_NUM)) {
        array_push($lang, $row[1]);
    }

    // The first two columns are id and category, so we remove them
    array_splice($lang, 0, 2);

    return $lang;
}

function getTexts( $category, $lang ) {
    $query = "SELECT id, " . $lang . " FROM site_text WHERE category='" . $category . "'";
    $res = $GLOBALS['db']->query($query);

    $texts = [];
    while ($row = $res->fetchArray()) {
        $texts[$row['id']] = $row[$lang];
    }

    return $texts;
}

function getTextInSpecifiedLanguage( $str, $lang ) {
    $query = "SELECT " . $lang . " FROM site_text WHERE id='" . $str . "'";
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray();
    $text = $row[$lang];

    return $text;
}
?>
