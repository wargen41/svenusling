<fieldset>
<legend>Bilder</legend>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "Poster",
    "attributes" => array(
        "required" => false,
        "name" => "posterimage",
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
        "name" => "largeimage",
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

Lista alla mediafiler som kopplats till filmen

</div>

</fieldset>
