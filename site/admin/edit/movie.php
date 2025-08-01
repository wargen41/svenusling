<?php
// Collect movie info
$query = implode(' ', [
    "SELECT *",
    "FROM movies",
    "WHERE MovieID = :id",
]);

// OBS! Nedan ska uppdateras till sifferkoll när det finns en sådan
$id = sanitizeSingleLineText($_GET['id']) ?? null;

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
        "size" => 1,
        "name" => "rating",
        "id" => "edit-movie-rating",
        "value" => $movies['Rating']
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
