<?php

$errors = [];

$table = "movies";

$columns = array(
    "Title",
    "Year",
    "Rating",
    "Hidden"
);
$columnsStr = implode(', ', $columns);

$statement = 'INSERT INTO '.$table.' ('.$columnsStr.') VALUES(:Title, :Year, :Rating, 1)';

$title = sanitizeSingleLineText($_POST['title']);
// OBS! Nedan ska uppdateras till sifferkoll när det finns en sådan
// Samt även begränsas i längd
$year = sanitizeSingleLineText($_POST['year']);
$rating = sanitizeSingleLineText($_POST['rating']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':Title', $title, SQLITE3_TEXT);
$stmt->bindValue(':Year', $year, SQLITE3_TEXT);
$stmt->bindValue(':Rating', $rating, SQLITE3_NUM);

$result = $stmt->execute();

if (!$result) {
    array_push($errors, $db->lastErrorMsg());
}

if (empty($errors)) {
    $query = [];
    $queryStr = "";
    // Make sure the query string contains "mata",
    // without removing any other existing querys
    $url = parse_url($_SERVER['HTTP_REFERER']);
    if(isset($url['query'])){
        parse_str($url['query'], $query);
    }
    if(!isset($query['mata'])){
        $query['mata'] = 1;
    }
    $queryStr = http_build_query($query);

    $locStr = $url['scheme'].'://'.$url['host'].$url['path'].'?'.$queryStr;
    header("Location: {$locStr}");
    exit;
}
else {
    echo "Update failed!<br>";
    echo implode('<br>', $errors);
}

?>
