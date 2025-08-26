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

<article>

<?php
echo pgHeadingHTML('movies', 'Redigera filmobjekt', $movies['Title']);
?>

<?php
$allowedSections = array(
    "general",
    "connections",
    "media",
    "genres",
);
$sections = array(
    "general" => "Allmänt",
    "media" => "Media",
    "genres" => "Genrer",
);
$sectionTexts = array(
    "general" => "Betyg, titel etc.",
    "media" => "Bilder med mera",
    "genres" => "Klassificera efter genre",
);
$section = null;

if(isset($_GET) && isset($_GET['section'])){
    $section = sanitizeByList($_GET['section'], $allowedSections);
    if(is_null($section)){
        echo big404Image();
    }
}

if(isset($section)){

    $formName = "movie-".$section;

    echo '<form method="post" action="update/">';
    echo '<input type="hidden" name="form" value="'.$formName.'">';

    include $GLOBALS['my_dir'].'admin/edit/movie/'.$section.'.php';

    echo '<div class="form-actions">';
    echo '<input type="submit" value="Spara">';

    $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $back_url = removeQueryFromURL($page_url, 'section');
    echo htmlWrap('a', 'Avbryt', array(
        "href" => $back_url,
        "class" => "button"
    ));

    echo '</div>';

    echo '</form>';

}
else{
    $formName = "movie-type";
    echo '<form method="post" action="update/">';
    echo '<input type="hidden" name="form" value="'.$formName.'">';
    include $GLOBALS['my_dir'].'admin/edit/movie/type.php';
    echo '</form>';

    if(typeCanBePartOfSeries($movies['Type'])){
        echo "<fieldset>";
        echo "<legend>Kopplingar</legend>";

        echo '<div class="input-row">';

        $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $section_url = addQueryToURL($page_url, 'section', 'connections', true);
        $section_text = htmlWrap('span', 'Serietillhörighet etc.');
        echo htmlWrap('p', htmlWrap('a', htmlWrap('button', 'Redigera kopplingar'), array(
            "href" => $section_url
        )).$section_text);

        echo "</div>";
        echo "</fieldset>";
    }

    /* Section links */
    $sectionsHTML = "";
    foreach($sections as $sect => $title){
        $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $section_url = addQueryToURL($page_url, 'section', $sect, true);

        $section_text = "";
        if(isset($sectionTexts[$sect])){
            $section_text = htmlWrap('span', $sectionTexts[$sect]);
        }
        $sectionsHTML .= htmlWrap('li', htmlWrap('a', htmlWrap('button', $title), array(
            "href" => $section_url
        )).$section_text);
    }

    echo htmlWrap('ul', $sectionsHTML);

    $formName = "movie-visibility";
    echo '<form method="post" action="update/">';
    echo '<input type="hidden" name="form" value="'.$formName.'">';
    include $GLOBALS['my_dir'].'admin/edit/movie/visibility.php';
    echo '</form>';

}

?>

</article>

<?php
print_rPRE($movies);

// $back = $_SERVER['HTTP_REFERER'] ?? null;
// if(!is_null($back)){
//     echo htmlWrap('p', htmlWrap('a', 'Tillbaka', array(
//         "href" => $back
//     )));
// }

?>

</main>
