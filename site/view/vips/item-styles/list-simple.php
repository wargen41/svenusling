<?php

/* Don't forget to wrap in ul or ol where this is included */

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $name = htmlSafeOutput($row['Name']);

    $nameHTML = $name ?? '???';
    $nameHTML = htmlWrap('span', $nameHTML, array(
        "class" => "name"
    ));

    $vipHTML = $nameHTML;
    echo htmlWrap('li', $vipHTML);
}

?>
