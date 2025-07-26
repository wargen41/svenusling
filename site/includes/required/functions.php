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
    // There are not always breaks in the switch (is intended)
    switch($viewType) {
        case "movies":
            $str = "Ms {$str}";
        case "movie":
            $str = "M {$str}";
        case "persons":
            $str = "Ps {$str}";
        case "person":
            $str = "P {$str}";
        default:
            // We may also do something for each case without a break statement
            // or where there was no matching case
    }

    $headingHTML = htmlWrap('h1', $str);
    if($text2){
        $headingHTML = htmlWrap('hgroup', $headingHTML.htmlWrap('p', $text2));
    }
    return $headingHTML;
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
function print_rPRE($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

?>
