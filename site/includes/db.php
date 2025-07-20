<?php

// Automatically open connection to db where this is included
openDB();

function openDB() {
    $GLOBALS['db'] = new SQLite3($GLOBALS['db_path'] . $GLOBALS['db_name']);
}
function closeDB() {
    $GLOBALS['db']->close();
}

// function dbPrepareStringForBinding($array) {
//     foreach ($array as $key => $value) {
//         $result[] = sprintf('%s=:%s', $key, $key);
//     }
//     $string = implode(', ', $result);
//
//     return $string;
// }
//
function dbArrayToStringForBinding($array) {
    foreach ($array as $name) {
        $result[] = sprintf('%s=:%s', $name, $name);
    }
    $string = implode(', ', $result);

    return $string;
}

function countAll($table) {
    $query = "SELECT COUNT(*) FROM ".$table;
    $res = $GLOBALS['db']->querySingle($query);

    return $res;
}

function getSiteVars() {
    $res = $GLOBALS['db']->query('SELECT * FROM site');

    $vars = [];
    while ($row = $res->fetchArray()) {
        $vars[$row['var']] = $row['value'];
    }

    return $vars;
}

function getSiteVarsKeys() {
    $res = $GLOBALS['db']->query('SELECT var FROM site');

    $vars = [];
    while ($row = $res->fetchArray()) {
        array_push($vars, $row['var']);
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

function getTextInSpecifiedLanguage( $id, $lang ) {
    $query = "SELECT " . $lang . " FROM site_text WHERE id='" . $id . "'";
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray();
    $text = $row[$lang];

    return $text;
}

function countArticles() {
    return countAll('site_articles');
}

function getAllArticles() {
    $query = "SELECT * FROM site_articles";
    $res = $GLOBALS['db']->query($query);

    $articles = [];
    while ($row = $res->fetchArray()) {
        $id = $row['id'];
        $lang = $row['lang'];
        if(!array_key_exists($id, $articles)) {
            $articles[$id] = [];
        }
        $articles[$id][$lang] = array(
            "title"=>$row['title'],
            "ingress"=>$row['ingress'],
            "body"=>$row['body'],
            "author"=>$row['author'],
            "date"=>$row['date']
        );
    }

    return $articles;
}

function getArticleInAllLanguages( $id ) {
    $query = "SELECT * FROM site_articles WHERE id='" . $id . "'";
    $res = $GLOBALS['db']->query($query);

    $article = [];
    while ($row = $res->fetchArray()) {
        $lang = $row['lang'];
        $langArticle = [];
        $langArticle['title'] = $row['title'];
        $langArticle['ingress'] = $row['ingress'];
        $langArticle['body'] = $row['body'];
        $langArticle['author'] = $row['author'];
        $langArticle['date'] = $row['date'];
        $article[$lang] = $langArticle;
    }

    return $article;
}
?>
