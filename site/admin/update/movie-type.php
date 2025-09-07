<?php

$errors = [];

$table = "movies";

$columns = array(
    "Type",
);
$fieldsToSet = dbArrayToColumnStringForBinding($columns);

$statement = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE MovieID=:MovieID';

$movieid = sanitizeIntegers($_POST['movieid']);
$type = sanitizeByList($_POST['type'], typesOfMovie());

if(is_null($type)) {
    $errorHTML = htmlWrap('p', 'Felaktig filmtyp angiven!');
    if(isset($_POST) && isset($_POST['redirect'])){
        $errorHTML .= htmlWrap('a', 'Tillbaka', array(
            "href" => sanitizeRedirect($_POST['redirect'])
        ));
    }
    array_push($errors, $errorHTML);
}

$stmt = $db->prepare($statement);
$stmt->bindValue(':MovieID', $movieid, SQLITE3_TEXT);
$stmt->bindValue(':Type', $type, SQLITE3_TEXT);
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
