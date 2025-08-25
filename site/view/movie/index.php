<?php

require __DIR__.'/../../includes/collections/default.php';

// Sen ska vi nog köra på samma upplägg som för listorna
// med viewType och listType och listStyle
// (fast kanske skippa listType och kalla det pageStyle istället)
// viewType vill jag ha på alla sidor, så jag kan utnyttja pageHeading-funktionen

// Detta är bara för att snabbt få ut all data på sidan, så man kan enklare kolla
// saker under utvecklingen av admindelen för film- och persondata
// Sen kommer det ju bli en del joins och sånt också
// Nu gör jag bara några väldigt simpla queries
if(isset($_GET) && isset($_GET['id'])){
    $id = sanitizeIntegers($_GET['id']);

    // movies
    $query = implode(' ', [
        "SELECT *",
        "FROM movies",
        "WHERE MovieID = ".$id,
    ]);
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray(SQLITE3_ASSOC);

    $movies = array();
    foreach($row as $key => $value){
        if($value === null){
            $value = 'NULL';
        }if(is_int($value)){
            $value = (string)$value;
        }
        $movies[$key] = htmlSafeOutput($value);
    }

    $ratingStr = '';
    if($row['Rating']!==''){
        $ratingStr = suRating($row['Rating']);
    }

    $originalTitle = '';
    if($row['OriginalTitle']!==''){
        $originalTitle = $row['OriginalTitle'];
    }

    // movies_genres
    $query = implode(' ', [
        "SELECT *",
        "FROM movies_genres",
        "WHERE MovieID = ".$id,
    ]);
    $res = $GLOBALS['db']->query($query);

    $movies_genres = array();
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $value = $row['GenreID'];
        if($value === null){
            $value = 'NULL';
        }
        array_push($movies_genres, htmlSafeOutput($value));
    }

    // movies_persons
    $query = implode(' ', [
        "SELECT *",
        "FROM movies_persons",
        "WHERE MovieID = ".$id,
    ]);
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray(SQLITE3_ASSOC);
    if($row===false){ $row = []; }

    $movies_persons = array();
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        array_push($movies_persons, htmlSafeOutput($row));
    }

    $page_title = $movies['Title'];
}else{
    $page_title = 'Lost you are???';
}


require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<article>

<?php

echo pgHeadingHTML('movie', $page_title, $originalTitle);
//echo htmlWrap('hgroup', htmlWrap('h1', $page_title).htmlWrap('p', $originalTitle));
// echo htmlWrap('h1', $page_title);
// echo htmlWrap('p', $originalTitle);
echo htmlWrap('p', $ratingStr, array(
    "style" => "text-align: center;"
));

if(strpos($page_title, '???') !== false){
    echo big404Image();
}else{
    echo htmlWrap('h2', 'movies');
    print_rPRE($movies);
    echo htmlWrap('h2', 'movies_genres');
    print_rPRE($movies_genres);
    echo htmlWrap('h2', 'movies_persons');
    print_rPRE($movies_persons);
}

?>

</article>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
