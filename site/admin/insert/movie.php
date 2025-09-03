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
$year = sanitizeIntegers($_POST['year'], 4);
$rating = sanitizeIntegers($_POST['rating'], 1);

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
    $location = $_SERVER['HTTP_REFERER'] ?? $GLOBALS['base_uri'].'/admin/';
    $location = addQueryToURL($location, 'mata', 1);
    header("Location: {$location}");
    exit;
}
else {
    echo htmlWrap('h1', "Uppdatering misslyckades!");
    echo implode('<br>', $errors);
}

?>
