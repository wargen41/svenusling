<?php
$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$location = $page_url;
echo htmlInput(array(
    "attributes" => array(
        "type" => "hidden",
        "name" => "redirect",
        "id" => "edit-movie-redirect",
        "value" => $location
    )
));
?>

<fieldset>
<legend>Typ</legend>

<?php
echo htmlInput(array(
    "attributes" => array(
        "type" => "hidden",
        "name" => "movieid",
        "id" => "edit-movie-movieid",
        "value" => $movies['MovieID'] ?? ''
    )
));
?>

<div class="input-row">

<?php
$movieTypes = typesOfMovieTexts();
$typeOptions = array_merge(array("" => "--"), $movieTypes);
echo htmlSelect(array(
    //"label" => "Typ",
    "options" => $typeOptions,
    "selected" => $movies['Type'] ?? '',
    "attributes" => array(
        "required" => true,
        "name" => "type",
        "id" => "edit-movie-type",
    )
));

//if(!typeCanBePartOfSeries($movies['Type'])){
    echo htmlInput(array(
        "attributes" => array(
            "type" => "submit",
            "value" => "Spara"
        )
    ));
//}


?>

</div>

</fieldset>

<?php
if(typeCanBePartOfSeries($movies['Type'])){
    echo "<fieldset>";
    echo "<legend>Kopplingar</legend>";

    echo '<div class="input-row">';

    $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $section_url = addQueryToURL($page_url, 'section', 'connections', true);
    $section_text = htmlWrap('span', 'Serietillhörighet etc.');
    echo htmlWrap('p', htmlWrap('a', htmlWrap('button', 'Kopplingar'), array(
        "href" => $section_url
    )).$section_text);

    echo "</div>";
    echo "</fieldset>";

}
?>
