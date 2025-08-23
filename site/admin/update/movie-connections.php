<?php

$errors = [];

$table = "movies";

$columns = array(
    "SeriesID",
    "SeasonID",
    "Number",
    "Number2"
);
$fieldsToSet = dbArrayToStringForBinding($columns);

$statement = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE MovieID=:MovieID';

$movieid = sanitizeIntegers($_POST['movieid']);
$seriesid = sanitizeIntegers($_POST['seriesid']);
$seasonid = sanitizeIntegers($_POST['seasonid']);
$number = sanitizeIntegers($_POST['number']);
$number2 = sanitizeIntegers($_POST['number2']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':MovieID', $movieid, SQLITE3_TEXT);
$stmt->bindValue(':SeriesID', $seriesid, is_null($seriesid) ? SQLITE3_NULL : SQLITE3_NUM);
$stmt->bindValue(':SeasonID', $seasonid, is_null($seasonid) ? SQLITE3_NULL : SQLITE3_NUM);
$stmt->bindValue(':Number', $number, is_null($number) ? SQLITE3_NULL : SQLITE3_NUM);
$stmt->bindValue(':Number2', $number2, is_null($number2) ? SQLITE3_NULL : SQLITE3_NUM);
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
