<?php

$errors = [];

$table = "movies";

$columns = array(
    "Hidden"
);
$fieldsToSet = dbArrayToStringForBinding($columns);

$statement = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE MovieID=:MovieID';

$movieid = sanitizeIntegers($_POST['movieid']);
$hidden = sanitizeBoolean($_POST['hidden']);

// Check other conditions
$query = implode(' ', [
    "SELECT Type",
    "FROM movies",
    "WHERE MovieID = :id",
]);
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $movieid, SQLITE3_NUM);
$res = $stmt->execute();
$movie = $res->fetchArray(SQLITE3_ASSOC);

if($movie['Type'] === '' || $movie['Type'] == null){
    $errorHTML = htmlWrap('p', 'Kan inte publicera utan definierad filmtyp');
    if(isset($_POST) && isset($_POST['redirect'])){
        $errorHTML .= htmlWrap('a', 'Tillbaka', array(
            "href" => sanitizeRedirect($_POST['redirect'])
        ));
    }
    array_push($errors, $errorHTML);
}
else{
    $stmt = $db->prepare($statement);
    $stmt->bindValue(':MovieID', $movieid, SQLITE3_TEXT);
    $stmt->bindValue(':Hidden', $hidden, SQLITE3_NUM);

    $result = $stmt->execute();

    if (!$result) {
        array_push($errors, $db->lastErrorMsg());
    }
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
