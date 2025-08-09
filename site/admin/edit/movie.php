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
    "general" => "Allmänt",
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
        include $GLOBALS['my_dir'].'admin/edit/movie/general.php';
    }
    else if($section === 'media'){
        include $GLOBALS['my_dir'].'admin/edit/movie/media.php';
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
