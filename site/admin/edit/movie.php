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
    "persons"
);
$autoSections = array(
    "general" => "Allmänt",
    "media" => "Media",
    "genres" => "Genrer",
    "persons" => "Personer",
);
$sectionTexts = array(
    "general" => "Betyg, titel etc.",
    "connections" => "Serietillhörighet etc.",
    "media" => "Bilder med mera",
    "genres" => "Klassificera efter genre",
    "persons" => "Lista folk som jobbat med filmen",
);
$section = null;

if(isset($_GET) && isset($_GET['section'])){
    $section = sanitizeByList($_GET['section'], $allowedSections);
    if(is_null($section)){
        echo big404Image();
    }
}

if(isset($section)){
    include $GLOBALS['my_dir'].'admin/edit/movie/'.$section.'.php';
}
else{
    include $GLOBALS['my_dir'].'admin/edit/movie/type.php';

    if(typeCanBePartOfSeries($movies['Type'])){
        $sect = 'connections';
        echo editMoreLinkHTML('Kopplingar', $sect, 'Redigera kopplingar', $sectionTexts[$sect]);
    }

    /* Section links */
    $sectionsHTML = "";
    foreach($autoSections as $sect => $title){
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

    echo htmlFieldset('Redigera mera', htmlWrap('ul', $sectionsHTML));

    include $GLOBALS['my_dir'].'admin/edit/movie/visibility.php';

    echo formActionsHTML(['done']);

}

?>

</article>

<?php
print_rPRE($movies);
?>

</main>
