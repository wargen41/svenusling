<?php
$GLOBALS['my_site'] = getSiteVars();

$GLOBALS['my_supported_languages'] = getTextLanguages();
$GLOBALS['my_language'] = tbs_get_client_browser_lang( $my_supported_languages, $my_supported_languages[0] );

function getStr($str) {
    return getTextInSpecifiedLanguage( $str, $GLOBALS['my_language'] );
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

?>
