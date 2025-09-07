<?php

$errors = [];

$table = "movies";

$columns = array(
    "Title",
    "Year",
    "OriginalTitle",
    "Rating",
    "Sorting",
    "PublishedDate",
    "ViewDate",
    "IMDbID"
);
$fieldsToSet = dbArrayToColumnStringForBinding($columns);

$statement = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE MovieID=:MovieID';

$movieid = sanitizeIntegers($_POST['movieid']);
$title = sanitizeSingleLineText($_POST['title']);
$year = sanitizeIntegers($_POST['year'], 4);
$originaltitle = sanitizeSingleLineText($_POST['originaltitle']);
$rating = sanitizeIntegers($_POST['rating'], 1);
$sorting = sanitizeSingleLineText($_POST['sorting']);
$publisheddate = sanitizeDate($_POST['publisheddate']);
$viewdate = sanitizeDate($_POST['viewdate']);
$imdbid = sanitizeSingleLineText($_POST['imdbid']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':MovieID', $movieid, SQLITE3_TEXT);
$stmt->bindValue(':Title', $title, SQLITE3_TEXT);
$stmt->bindValue(':Year', $year, SQLITE3_TEXT);
$stmt->bindValue(':OriginalTitle', $originaltitle, SQLITE3_TEXT);
$stmt->bindValue(':Rating', $rating, SQLITE3_NUM);
$stmt->bindValue(':Sorting', $sorting, SQLITE3_TEXT);
$stmt->bindValue(':PublishedDate', $publisheddate, SQLITE3_TEXT);
$stmt->bindValue(':ViewDate', $viewdate, SQLITE3_TEXT);
$stmt->bindValue(':IMDbID', $imdbid, SQLITE3_TEXT);

$result = $stmt->execute();

if (!$result) {
    array_push($errors, $db->lastErrorMsg());
}

if (empty($errors)) {
    if(isset($_POST) && isset($_POST['redirect'])){
        $location = sanitizeRedirect($_POST['redirect']);
        header("Location: {$location}");
        exit;
    }
    else{
        $location = $_SERVER['HTTP_REFERER'] ?? $GLOBALS['base_uri'].'/admin/';
        header("Location: {$location}");
        exit;
    }
}
else {
    echo "Update failed!<br>";
    echo implode('<br>', $errors);
}

?>
