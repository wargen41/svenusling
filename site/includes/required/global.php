<?php

$GLOBALS['my_site'] = getSiteVars();

$GLOBALS['my_supported_languages'] = getTextLanguages();
$GLOBALS['my_language'] = tbs_get_client_browser_lang( $GLOBALS['my_supported_languages'], $GLOBALS['my_site']['default_language'] );

function big404Image() {
    return "<img class=\"lost\" alt=\"ERROR\" src=\"{$GLOBALS['base_uri']}{$GLOBALS['my_site']['bigerror']}\">";
}

function getStr($id) {
    return getTextInSpecifiedLanguage( $id, $GLOBALS['my_language'] );
}

function getArticle($id) {
    $articleVersions = getArticleInAllLanguages( $id );

    $lang = $GLOBALS['my_language'];
    if (!array_key_exists( $lang, $articleVersions )) {
        $supported = $GLOBALS['my_supported_languages'];
        // Loop through the array of supported languages and use the first one which exists
        foreach ($supported as $supportedLang) {
            if (array_key_exists($supportedLang, $articleVersions)) {
                $lang = $supportedLang;
                break; // Stop after finding the first available language
            }
        }
    }
    return $articleVersions[$lang];
}

function dynamicArticle($id) {
    $theArticle = getArticle($id);
    $theId = $id;
    include $GLOBALS['my_dir'].'modules/dynamic-article.php';
}

function includeModule($file) {
    include $GLOBALS['my_dir'].'modules/' . $file;
}

// https://medium.com/@akmashish15/how-to-detect-browser-language-in-php-87442c39496a
/* Detect Browser langagues using PHP */
function tbs_get_client_browser_lang( $checklanguages, $default ){
    //echo $default;
    if( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ){
        $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        foreach ( $langs as $value) {
            $getlang = substr( $value, 0,2 );
            if( in_array( $getlang, $checklanguages ) ){
                return $getlang;
            }
        }
    }
    return $default;
}

function addQueryToURL(string $url, string $key, string|int $value): string {
    $query = [];
    $queryStr = "";
    // Make sure the query string contains $key,
    // without removing any other existing querys
    $url = parse_url($_SERVER['HTTP_REFERER']);
    if(isset($url['query'])){
        parse_str($url['query'], $query);
    }
    if(!isset($query[$key])){
        $query[$key] = $value;
    }
    $queryStr = http_build_query($query);

    $queryURL = $url['scheme'].'://'.$url['host'].$url['path'].'?'.$queryStr;
    return $queryURL;
}

function sanitizeQuery(string $value): string {
    // Remove everything but lower case letters a to z and integers
    $value = preg_replace('/[^a-z0-9]/', '', $value);

    return $value;
}


?>
