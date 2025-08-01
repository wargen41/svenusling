<?php

$errors = [];

$table = "movies";

$columns = array(
    "Title",
    "Sorting",
    "Year",
    "Rating",
    "Hidden"
);
$columnsStr = implode(', ', $columns);

$statement = 'INSERT INTO '.$table.' ('.$columnsStr.') VALUES(:Title, :Sorting, :Year, :Rating, 1)';

$title = sanitizeSingleLineText($_POST['title']);
$sorting = autoSortingString($title);
// OBS! Nedan ska uppdateras till sifferkoll när det finns en sådan
// Samt även begränsas i längd
$year = sanitizeSingleLineText($_POST['year']);
$rating = sanitizeSingleLineText($_POST['rating']);

$stmt = $db->prepare($statement);
$stmt->bindValue(':Title', $title, SQLITE3_TEXT);
$stmt->bindValue(':Sorting', $sorting, SQLITE3_TEXT);
$stmt->bindValue(':Year', $year, SQLITE3_TEXT);
$stmt->bindValue(':Rating', $rating, SQLITE3_NUM);

$result = $stmt->execute();

if (!$result) {
    array_push($errors, $db->lastErrorMsg());
}

if (empty($errors)) {
    $locStr = addQueryToURL($_SERVER['HTTP_REFERER'], 'mata', 1);
    header("Location: {$locStr}");
    exit;
}
else {
    echo "Update failed!<br>";
    echo implode('<br>', $errors);
}

?>
