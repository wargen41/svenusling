<?php

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
