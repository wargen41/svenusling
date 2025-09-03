<?php

$errors = [];

$table = "movies_genres";

$columns = array(
    "MovieID",
    "GenreID",
);
$columnsStr = implode(', ', $columns);
$fieldsToSet = dbArrayToStringForBinding($columns);

$statement = 'INSERT INTO '.$table.' ('.$columnsStr.') VALUES(:MovieID, :GenreID)';

$movieid = sanitizeIntegers($_POST['movieid']);
$genreid = sanitizeIntegers($_POST['genreid']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':MovieID', $movieid, SQLITE3_NUM);
$stmt->bindValue(':GenreID', $genreid, SQLITE3_NUM);

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
    echo htmlWrap('h1', "Uppdatering misslyckades!");
    echo implode('<br>', $errors);
}

?>
