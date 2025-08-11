<fieldset>
<legend>Grundläggande</legend>

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
    "label" => "Typ",
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
        "value" => "Ändra typ"
    )
));
?>

</div>

<div class="input-row">

<?php
$hidden = $movies['Hidden'];
$visibilityText = ($hidden > 0) ? 'Publicera' : 'Dölj';
echo htmlInput(array(
    "label" => "Synlighet",
    "attributes" => array(
        "type" => "button",
        "id" => "edit-movie-visibility",
        "value" => $visibilityText
    )
));
?>

</div>

</fieldset>
