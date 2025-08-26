<form method="post" action="update/">
<input type="hidden" name="form" value="movie-general">

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
<legend>Grunduppgifter</legend>

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
echo htmlInput(array(
    "label" => "Titel",
    "attributes" => array(
        "required" => true,
        "name" => "title",
        "id" => "edit-movie-title",
        "value" => $movies['Title'] ?? ''
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "År",
    "attributes" => array(
        "required" => false,
        "type" => "number",
        "min" => 1888,
        "max" => 2099,
        "size" => 4,
        "name" => "year",
        "id" => "edit-movie-year",
        "value" => $movies['Year'] ?? ''
    )
));
?>

</div>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "Originaltitel",
    "attributes" => array(
        "required" => false,
        "name" => "originaltitle",
        "id" => "edit-movie-originaltitle",
        "value" => $movies['OriginalTitle'] ?? ''
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "Betyg",
    "attributes" => array(
        "required" => true,
        "type" => "number",
        "min" => 0,
        "max" => 5,
        "size" => 1,
        "name" => "rating",
        "id" => "edit-movie-rating",
        "value" => $movies['Rating']
    )
));
?>

</div>

</fieldset>

<fieldset>
<legend>Dolda uppgifter</legend>

<p>Dessa uppgifter visas inte för besökare (men de kan ändå påverka hur vissa andra saker ser ut).</p>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "Sorteringstitel",
    "attributes" => array(
        "required" => true,
        "name" => "sorting",
        "id" => "edit-movie-sorting",
        "value" => $movies['Sorting'] ?? ''
    )
));
?>

</div>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "Publiceringsdatum",
    "attributes" => array(
        "required" => false,
        "type" => "date",
        "name" => "publisheddate",
        "id" => "edit-movie-publisheddate",
        "value" => $movies['PublishedDate'] ?? ''
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "Tittdatum",
    "attributes" => array(
        "required" => false,
        "type" => "date",
        "name" => "viewdate",
        "id" => "edit-movie-viewdate",
        "value" => $movies['ViewDate'] ?? ''
    )
));
?>

</div>

<div class="input-row">
<?php
echo htmlInput(array(
    "label" => "IMDb:s id",
    "attributes" => array(
        "required" => false,
        "name" => "imdbid",
        "id" => "edit-movie-imdbid",
        "value" => $movies['IMDbID'] ?? ''
    )
));
?>

</div>

</fieldset>

<?php
echo formActionsHTML();
?>

</form>

