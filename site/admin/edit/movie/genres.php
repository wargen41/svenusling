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
<legend>Lägg till genre</legend>

<div class="input-row">
<?php
// Collect movie genre info
$query = implode(' ', [
    "SELECT *",
    "FROM movies_genres",
    "WHERE MovieID = :id",
]);

$id = sanitizeIntegers($_GET['id']) ?? null;

$stmt = $db->prepare($query);
$stmt->bindValue(':id', $id, SQLITE3_NUM);

$res = $stmt->execute();

$movies_genres = array();
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    array_push($movies_genres, $row['GenreID']);
}

$emptyOption = array(
    "value" => "",
    "text" => "--"
);
$movieGenres = dbGetGenres(true);
$genreOptions = array();
foreach($movieGenres as $item) {
    $disabled = false;
    if(in_array($item['GenreID'], $movies_genres)){
        $disabled = true;
    }
    array_push($genreOptions, array(
        "value" => $item['GenreID'],
        "text" => $item['sv'],
        "disabled" => $disabled
    ));
}
array_unshift($genreOptions, $emptyOption);
echo htmlSelect(array(
    "options" => $genreOptions,
    "attributes" => array(
        "required" => false,
        "name" => "addgenre",
        "id" => "edit-movie-add-genre"
    )
));
?>

</div>

</fieldset>

<fieldset>
<legend>Associerade genrer</legend>

<div class="input-row">

Här ska vi lista alla genrer som kopplats till filmen

<?php

print_rPRE($movies_genres);
?>

</div>

</fieldset>
