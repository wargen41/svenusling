<?php
// Collect movie info
$query = implode(' ', [
    "SELECT *",
    "FROM movies",
    "WHERE MovieID = :id",
]);

$id = sanitizeIntegers($_GET['id']) ?? null;

$stmt = $db->prepare($query);
$stmt->bindValue(':id', $id, SQLITE3_NUM);

$res = $stmt->execute();

$movies = $res->fetchArray(SQLITE3_ASSOC);

?>

<main id="main-content">

<h1>Redigera film</h1>

<?php
$sections = array(
    "general" => "Grunduppgifter",
    "media" => "Media"
);
$longestSectionNameLength = 0;
foreach($sections as $key => $value){
    if(strlen($key) > $longestSectionNameLength){
        $longestSectionNameLength = strlen($key);
    }
}

if(isset($_GET) && isset($_GET['section'])){

    $section = sanitizeLettersLower($_GET['section'], $longestSectionNameLength);
    $formName = "movie-".$section;

    echo '<form method="post" action="update/">';
    echo '<input type="hidden" name="form" value="'.$formName.'">';

    if($section === 'general'){
        // Grunduppgifter
        $fieldsetHTML = htmlWrap('legend', 'Grunduppgifter');

        $inputsHTML = array();

        array_push($inputsHTML, htmlInput(array(
            "label" => "Titel",
            "attributes" => array(
                "required" => true,
                "name" => "title",
                "id" => "edit-movie-title",
                "value" => $movies['Title'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "Originaltitel",
            "attributes" => array(
                "required" => false,
                "name" => "originaltitle",
                "id" => "edit-movie-originaltitle",
                "value" => $movies['OriginalTitle'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "År",
            "attributes" => array(
                "required" => false,
                "type" => "number",
                "min" => 1888,
                "max" => 2099,
                "size" => 4,
                "name" => "year",
                "id" => "edit-movie-year",
                "value" => $movies['Year'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "Betyg",
            "attributes" => array(
                "required" => true,
                "type" => "number",
                "min" => 0,
                "max" => 5,
                "size" => 1,
                "name" => "rating",
                "id" => "edit-movie-rating",
                "value" => $movies['Rating']
            )
        )));

        $fieldsetHTML .= implode("\n", $inputsHTML);
        echo htmlWrap('fieldset', $fieldsetHTML);

        // Dolda uppgifter
        $fieldsetHTML = htmlWrap('legend', 'Dolda uppgifter');
        $fieldsetHTML .= htmlWrap('p', 'Dessa uppgifter visas inte någonstans för vanliga besökare (men de kan ändå påverka hur vissa andra saker ser ut).');

        array_push($inputsHTML, htmlInput(array(
            "label" => "Sorteringstitel",
            "attributes" => array(
                "required" => true,
                "name" => "sorting",
                "id" => "edit-movie-sorting",
                "value" => $movies['Sorting'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "Publiceringsdatum",
            "attributes" => array(
                "required" => false,
                "type" => "date",
                "name" => "publisheddate",
                "id" => "edit-movie-publisheddate",
                "value" => $movies['PublishedDate'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "Tittdatum",
            "attributes" => array(
                "required" => false,
                "type" => "date",
                "name" => "viewdate",
                "id" => "edit-movie-viewdate",
                "value" => $movies['ViewDate'] ?? ''
            )
        )));

        array_push($inputsHTML, htmlInput(array(
            "label" => "IMDb:s id",
            "attributes" => array(
                "required" => false,
                "name" => "imdbid",
                "id" => "edit-movie-imdbid",
                "value" => $movies['IMDbID'] ?? ''
            )
        )));

        $fieldsetHTML .= implode("\n", $inputsHTML);
        echo htmlWrap('fieldset', $fieldsetHTML);
    }
    else if($section === 'media'){
        echo "MEDIA";
//         $fieldsetHTML = htmlWrap('legend', 'Media');
//
//         $inputsHTML = array();
//
//         array_push($inputsHTML, htmlInput(array(
//             "label" => "Titel",
//             "attributes" => array(
//                 "required" => true,
//                 "name" => "title",
//                 "id" => "edit-movie-title",
//                 "value" => $movies['Title'] ?? ''
//             )
//         )));
//
//         $fieldsetHTML .= implode("\n", $inputsHTML);
//
//         echo htmlWrap('fieldset', $fieldsetHTML);
    }

    echo '<input type="submit" value="Spara">';
    echo '</form>';

}else{
    $sectionsHTML = "";
    foreach($sections as $section => $title){
        $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $section_url = addQueryToURL($page_url, 'section', $section);
        $sectionsHTML .= htmlWrap('li', htmlWrap('a', $title, array(
            "href" => $section_url
        )));
    }

    echo htmlWrap('ul', $sectionsHTML);

}

print_rPRE($movies);

$back = $_SERVER['HTTP_REFERER'] ?? null;
if(!is_null($back)){
    echo htmlWrap('p', htmlWrap('a', 'Tillbaka', array(
        "href" => $back
    )));
}

?>

</main>
