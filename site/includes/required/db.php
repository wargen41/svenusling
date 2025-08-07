<?php

function openDB() {
    $GLOBALS['db'] = new SQLite3($GLOBALS['db_path'] . $GLOBALS['db_name']);
}
function closeDB() {
    $GLOBALS['db']->close();
}

function htmlSafeOutput(array|string|null $text): array|string {
    if(is_array($text)){
        return array_map('htmlSafeOutput', $text);
    }
    if(is_null($text)){
        return "";
    }
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function urlSafeOutput(array|string|null $text): array|string {
    // Kanske hitta på något mer/annat här sen
    return htmlSafeOutput($text);
}

function sanitizeIntegers(string $text, int $limit=0): string {
    $text = trim($text);
    // Remove everything but integers
    $text = preg_replace('/[^\d]/', '', $text);

    // Optionally limit length
    if ($limit > 0 && mb_strlen($text) > $limit) {
        die('Too long!');
    }

    return $text;
}

function sanitizeSingleLineText(string $text, int $limit=0): string {
    $text = trim($text);
    // Remove invisible control characters
    $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);

    // Optionally limit length
    if ($limit > 0 && mb_strlen($text) > $limit) {
        die('Too long!');
    }

    return $text;
}

function sanitizeMultiLineText(string $text, int $limit=0): string {
    $text = trim($text);
    // Remove invisible control characters but keep \r and \n
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

    // Optionally limit length
    if ($limit > 0 && mb_strlen($text) > $limit) {
        die('Too long!');
    }

    return $text;
}

function dbArrayToStringForBinding(array $arr): string {
    $result = [];
    foreach ($arr as $name) {
        array_push($result, sprintf('%s=:%s', $name, $name));
    }
    $str = implode(', ', $result);

    return $str;
}

function dbCountAllRows(string $table): int {
    $query = "SELECT COUNT(*) FROM ".$table;
    $res = $GLOBALS['db']->querySingle($query);

    return (int)$res;
}

function dbCountType(string $table, string $type): int {
    $query = "SELECT COUNT(*) FROM ".$table." WHERE Type='".$type."'";
    $res = $GLOBALS['db']->querySingle($query);

    return (int)$res;
}

function dbCountHiddenMovies(): int {
    $query = "SELECT COUNT(*) FROM movies WHERE Hidden=1";
    $res = $GLOBALS['db']->querySingle($query);

    return (int)$res;
}

function getKeysFromTable(string $table): array {
    $res = $GLOBALS['db']->query('SELECT * FROM '.$table.' LIMIT 1');
    $row = $res->fetchArray(SQLITE3_ASSOC);

    $keys = array_keys($row);

    return $keys;
}

function getFirstColumnValuesFromTable(string $table): array {
    $res = $GLOBALS['db']->query('SELECT var FROM '.$table);

    $keys = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        array_push($keys, $row['var']);
    }

    return $keys;
}

function getSiteVars(bool $htmlSafe=true): array {
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

function getTextLanguages(): array {
    $res = $GLOBALS['db']->query("PRAGMA table_info(site_text)");

    $lang = [];
    while ($row = $res->fetchArray(SQLITE3_NUM)) {
        array_push($lang, $row[1]);
    }

    // The first two columns are id and category, so we remove them
    array_splice($lang, 0, 2);

    return $lang;
}

function getAllTexts(bool $htmlSafe=true): array {
    $query = "SELECT * FROM site_text  ORDER BY category, id";
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

function getTexts(string $category, string $lang): array {
    $query = "SELECT id, ".$lang." FROM site_text WHERE category='".$category."'";
    $res = $GLOBALS['db']->query($query);

    $texts = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $texts[$row['id']] = $row[$lang];
    }

    return $texts;
}

function getTextInSpecifiedLanguage(string $id, string $lang): string {
    $query = "SELECT ".$lang." FROM site_text WHERE id='".$id."'";
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray(SQLITE3_ASSOC);
    $text = $row[$lang];

    return $text;
}

function countArticles(): int {
    return dbCountAllRows('site_articles');
}

function getAllArticles(): array {
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
            "title" => $row['title'],
            "ingress" => $row['ingress'],
            "body" => $row['body'],
            "author" => $row['author'],
            "date" => $row['date']
        );
    }

    return $articles;
}

function getArticleInAllLanguages(string $id): array {
    $query = "SELECT * FROM site_articles WHERE id='".$id."'";
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
