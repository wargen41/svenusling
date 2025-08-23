<?php

$errors = [];

$table = "movies";

$columns = array(
    "PosterImageID",
    "LargeImageID",
);
$fieldsToSet = dbArrayToStringForBinding($columns);

$statement = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE MovieID=:MovieID';

$movieid = sanitizeIntegers($_POST['movieid']);
$posterimageid = sanitizeIntegers($_POST['posterimageid']);
$largeimageid = sanitizeIntegers($_POST['largeimageid']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':MovieID', $movieid, SQLITE3_TEXT);
$stmt->bindValue(':PosterImageID', $posterimageid, is_null($posterimageid) ? SQLITE3_NULL : SQLITE3_NUM);
$stmt->bindValue(':LargeImageID', $largeimageid, is_null($largeimageid) ? SQLITE3_NULL : SQLITE3_NUM);
$result = $stmt->execute();

if (!$result) {
    array_push($errors, $db->lastErrorMsg());
}

echo $posterimageid;
echo '<br>'.$largeimageid;

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
