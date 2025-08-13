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
echo pgHeadingHTML('movies', 'Redigera film', $movies['Title']);
?>

<?php
$sections = array(
    "general" => "Allmänt",
    "media" => "Media"
);
$sectionTexts = array(
    "general" => "Betyg, titel etc.",
    "media" => "Bilder med mera"
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
        include $GLOBALS['my_dir'].'admin/edit/movie/general.php';
    }
    else if($section === 'media'){
        include $GLOBALS['my_dir'].'admin/edit/movie/media.php';
    }

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

    /* Section links */
    $sectionsHTML = "";
    foreach($sections as $section => $title){
        $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $section_url = addQueryToURL($page_url, 'section', $section);

        $section_text = "";
        if(isset($sectionTexts[$section])){
            $section_text = htmlWrap('span', $sectionTexts[$section]);
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
