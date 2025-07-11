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

?>
