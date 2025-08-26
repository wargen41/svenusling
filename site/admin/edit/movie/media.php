<?php
$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$location = removeQueryFromURL($page_url, 'section');
echo htmlInput(array(
    "attributes" => array(
        "type" => "hidden",
        "name" => "redirect",
        "id" => "edit-movie-redirect",
        "value" => $location
    )
));
?>

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

<fieldset>
<legend>Bilder</legend>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "Poster",
    "attributes" => array(
        "required" => false,
        "name" => "posterimageid",
        "id" => "edit-movie-posterimage",
        "value" => $movies['PosterImageID'] ?? ''
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "Omslagsbild (stor)",
    "attributes" => array(
        "required" => false,
        "name" => "largeimageid",
        "id" => "edit-movie-largeimage",
        "value" => $movies['LargeImageID'] ?? ''
    )
));
?>

</div>

</fieldset>

<fieldset>
<legend>Associerade mediafiler</legend>

<div class="input-row">

Här ska vi lista alla mediafiler som kopplats till filmen

</div>

</fieldset>
