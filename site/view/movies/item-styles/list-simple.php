<?php

/* This only creates list items */
/* Don't forget to wrap in ul or ol where this is included */

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $title = htmlSafeOutput($row['Title']);
    $year = htmlSafeOutput($row['Year']);
    $rating = htmlSafeOutput($row['Rating']);
    $id = urlSafeOutput($row['MovieID']);

    $href = $GLOBALS['base_uri']."/view/movie/?id={$id}";

    $titleHTML = $title ?? '???';
    $titleHTML = htmlWrap('a', $titleHTML, array(
        "href" => $href
    ));
    $titleHTML = htmlWrap('span', $titleHTML, array(
        "class" => "title"
    ));

    $yearHTML = $year ?? '';
    if($yearHTML != ''){
        $yearHTML = htmlWrap('span', "(<time>{$yearHTML}</time>)", array(
            "class" => "year"
        ));
    }

    $ratingHTML = $rating ?? '';
    if($ratingHTML != ''){
        $ratingHTML = suRating($ratingHTML);
        $ratingHTML = htmlWrap('span', "{$ratingHTML}", array(
            "class" => "rating"
        ));
    }

    $itemHTML = htmlWrap('span', "{$titleHTML} {$yearHTML}", array(
        "class" => "item"
    ));

    $movieHTML = "{$itemHTML} {$ratingHTML}";
    echo htmlWrap('li', $movieHTML);
}

?>
