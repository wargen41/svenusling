<?php

/* This only creates table rows */
/* Don't forget to wrap in table (and maybe tbody) where this is included */

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $title = htmlSafeOutput($row['Title']);
    $year = htmlSafeOutput($row['Year']);
    $rating = htmlSafeOutput($row['Rating']);

    $titleHTML = $title ?? '???';
    $titleHTML = htmlWrap('td', $titleHTML, array(
        "class" => "title"
    ));

    $yearHTML = $year ?? '';
    if($yearHTML != ''){
        $yearHTML = htmlWrap('td', "<time>{$yearHTML}</time>", array(
            "class" => "year"
        ));
    }

    $ratingHTML = $rating ?? '';
    if($ratingHTML != ''){
        $ratingHTML = suRating($ratingHTML);
        $ratingHTML = htmlWrap('td', "{$ratingHTML}", array(
            "class" => "rating"
        ));
    }

    $movieHTML = "{$titleHTML} {$yearHTML} {$ratingHTML}";
    echo htmlWrap('tr', $movieHTML);
}

?>
