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

print_rPRE($movies);
?>

<main id="main-content">

<h1>Redigera film</h1>

<form method="post" action="update/index.php">
<input type="hidden" name="form" value="movie">

<fieldset>
<legend>Grunduppgifter</legend>

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

</fieldset>

<fieldset>
<legend>Dolda uppgifter</legend>

<p>Dessa uppgifter visas inte någonstans för vanliga besökare (men de kan ändå påverka hur vissa andra saker ser ut).</p>

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

</fieldset>

<input type="submit">
</form>

<?php
$back = $_SERVER['HTTP_REFERER'] ?? null;
if(!is_null($back)){
    echo htmlWrap('p', htmlWrap('a', 'Tillbaka', array(
        "href" => $back
    )));
}
?>

</main>
