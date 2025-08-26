<form method="post" action="update/">
<input type="hidden" name="form" value="movie-connections">

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

<fieldset>
<legend>Kopplingar</legend>

<p>OBS! Redigering av kopplingar är än så länge väldigt grundläggande och har inte mycket hjälp för att göra det smidigare att använda. Man fyller i interna ID och där är ingen kontroll att det ifyllda ID:t faktiskt är något som man bör skapa en koppling till.</p>

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

<?php
// Sen behöver det här vara lite smartare, för att det ska vara enkelt för användaren
// Det ska räcka att välja vilken säsong ett avsnitt tillhör, så ska automatiskt serien
// fyllas i (eller kanske att man först väljer serie, därefter avsnitt)
// Men vi börjar så här, så att man kan se och redigera värdena under utvecklingsfasen
// även om det kan råka bli fel som inte ska vara möjliga senare
if(typeCanBePartOfSeries($movies['Type']) || typeCanBePartOfSeason($movies['Type'])) {
    echo '<div class="input-row">';
}

$inputType = typeCanBePartOfSeries($movies['Type']) ? 'text' : 'hidden';
$inputValue = $movies['SeriesID'] ?? '';
echo htmlInput(array(
    "label" => "Överliggande serie",
    "attributes" => array(
        "required" => false,
        "type" => $inputType,
        "value" => $inputValue,
        "name" => "seriesid",
        "id" => "edit-movie-seriesid",
    )
));

$inputType = typeCanBePartOfSeason($movies['Type']) ? 'text' : 'hidden';
$inputValue = $movies['SeasonID'] ?? '';
echo htmlInput(array(
    "label" => "Överliggande säsong",
    "attributes" => array(
        "required" => false,
        "type" => $inputType,
        "value" => $inputValue,
        "name" => "seasonid",
        "id" => "edit-movie-seasonid",
    )
));

if(typeCanBePartOfSeries($movies['Type']) || typeCanBePartOfSeason($movies['Type'])) {
    echo '</div>';
}
?>

<?php
if(typeCanBePartOfSeries($movies['Type']) || typeCanBePartOfSeason($movies['Type'])) {
    echo '<div class="input-row">';
}

$inputType = typeCanBePartOfSeries($movies['Type']) ? 'text' : 'hidden';
$inputValue = $movies['Number'] ?? '';
$inputLabel = 'Nummer';
if($movies['Type'] == 'episode') {
    $inputLabel = 'Avsnitt nummer';
}else if($movies['Type'] == 'season') {
    $inputLabel = 'Säsong nummer';
}else if($movies['Type'] == 'film') {
    $inputLabel = 'Film nummer';
}
echo htmlInput(array(
    "label" => $inputLabel,
    "attributes" => array(
        "required" => false,
        "type" => $inputType,
        "value" => $inputValue,
        "name" => "number",
        "id" => "edit-movie-number",
    )
));

$inputType = typeCanBePartOfSeries($movies['Type']) ? 'text' : 'hidden';
$inputValue = $movies['Number2'] ?? '';
$inputLabel = "&ndash;";
echo htmlInput(array(
    "label" => $inputLabel,
    "attributes" => array(
        "required" => false,
        "type" => $inputType,
        "value" => $inputValue,
        "name" => "number2",
        "id" => "edit-movie-number2",
    )
));

if(typeCanBePartOfSeries($movies['Type']) || typeCanBePartOfSeason($movies['Type'])) {
    echo '</div>';
}
?>

</fieldset>

<?php
echo formActionsHTML();
?>

</form>
