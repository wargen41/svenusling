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
$movieTypes = array(
    "film" => "Film",
    "series" => "Serie",
    "season" => "Säsong",
    "episode" => "Avsnitt",
    "miniseries" => "Miniserie",
    "filmseries" => "Filmserie"
);
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

echo htmlInput(array(
    "attributes" => array(
        "type" => "submit",
        "value" => "Spara"
    )
));
?>

</div>

</fieldset>
