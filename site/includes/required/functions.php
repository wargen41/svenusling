<?php

function pgTitle(string $text): string {
    return "{$text} | {$GLOBALS['my_site']['name']}";
}

/**
 * Use this for a uniform page heading.
 *
 * Different things may happen depending on the $viewType argument
 * @param string $viewType
 * @param string $text
 * @param ?string $text2
 * @return string
 */
function pgHeadingHTML(string $viewType, string $text, ?string $text2 = ""): string {
    $str = $text;
    switch($viewType) {
        case "movies":
            $str = "Ms {$str}";
            break;
        case "movie":
            $str = "M {$str}";
            break;
        case "persons":
            $str = "Ps {$str}";
            break;
        case "person":
            $str = "P {$str}";
            break;
        default:
    }

    $headingHTML = htmlWrap('h1', $str);
    if($text2){
        $headingHTML = htmlWrap('hgroup', $headingHTML.htmlWrap('p', $text2));
    }
    return $headingHTML;
}

function autoSortingString(string $title): string {
    // Här ska vi göra lite fixar sen, såsom att ta bort The från början av titeln etc.
    return $title;
}

/**
 * Converts an integer to a string of plus signs.
 *
 * If the number is 0 it returns a minus sign instead
 * @param int $arr
 * @return string
 */
function suRating(int $rating): string {
    if($rating == 0){
        return '-';
    }

    return str_pad('', $rating, '+');
}

// Not done yet
function resCountZero() {
    return '<p>Hittade inget</p>';
}

function print_rPRE($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

?>
