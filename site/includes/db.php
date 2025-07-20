<?php

// Automatically open connection to db where this is included
openDB();

function openDB() {
    $GLOBALS['db'] = new SQLite3($GLOBALS['db_path'] . $GLOBALS['db_name']);
}
function closeDB() {
    $GLOBALS['db']->close();
}

function htmlSafeOutput($text) {
    if(is_array($text)){
        return array_map('htmlSafeOutput', $text);
    }
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function sanitizeSingleLineText($text, $limit=0) {
    $text = trim($text);
    // Remove invisible control characters
    $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);

    // Optionally limit length
    if ($limit > 0 && mb_strlen($text) > $limit) {
        die('Too long!');
    }

    return $text;
}

function sanitizeMultiLineText($text, $limit=0) {
    $text = trim($text);
    // Remove invisible control characters but keep \r and \n
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

    // Optionally limit length
    if ($limit > 0 && mb_strlen($text) > $limit) {
        die('Too long!');
    }

    return $text;
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

function getSiteVars($htmlSafe=true) {
    $res = $GLOBALS['db']->query('SELECT * FROM site');

    $vars = [];
    if($htmlSafe == true){
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $vars[$row['var']] = htmlSafeOutput($row['value']);
        }
    }else{
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $vars[$row['var']] = $row['value'];
        }
    }

    return $vars;
}

function getSiteVarsKeys() {
    $res = $GLOBALS['db']->query('SELECT var FROM site');

    $vars = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
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

function getAllTexts($htmlSafe=true) {
    $query = "SELECT * FROM site_text";
    $res = $GLOBALS['db']->query($query);

    $texts = [];
    if($htmlSafe == true){
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            array_push($texts, htmlSafeOutput($row));
        }
    }else{
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            array_push($texts, $row);
        }
    }

    return $texts;
}

function getTexts( $category, $lang ) {
    $query = "SELECT id, " . $lang . " FROM site_text WHERE category='" . $category . "'";
    $res = $GLOBALS['db']->query($query);

    $texts = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $texts[$row['id']] = $row[$lang];
    }

    return $texts;
}

function getTextInSpecifiedLanguage( $id, $lang ) {
    $query = "SELECT " . $lang . " FROM site_text WHERE id='" . $id . "'";
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray(SQLITE3_ASSOC);
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
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
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
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
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
