<?php

// Don't forget to wrap in ul or ol where this is included

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $title = htmlSafeOutput($row['Title']);
    $year = htmlSafeOutput($row['Year']);
    $rating = htmlSafeOutput($row['Rating']);

    $titleHTML = $title ?? '???';
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
