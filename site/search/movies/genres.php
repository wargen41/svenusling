<?php
/* Returns all movies which have the specified genres */
// Tror vi tänker om här
// Känns som det är enklare att helt enkelt skapa en function för varje sorts sökning
// (och som eventuellt returnerar resultatet som en PHP-array)
// Var ett tag inne på att returnera resultatet som JSON, men tror det blir sämre prestanda
// än att köra ren PHP (spelar förmodligen ingen större roll, men ändå)
// Om jag senare vill kunna göra sökningar med dynamisk JavaScript får vi göra
// en lösning för det då

$errors = [];

$table = "movies_genres";

if (!is_array($_POST['genres']) || empty($_POST['genres'])) {
    array_push($errors, "Inga genrer valda!");
}else{
    $values = sanitizeIntegersArray($_POST['genres']);
    $limit = sanitizeIntegers($_POST['limit']) ?? 0;
    $placeholders = dbPlaceholdersArray($values);

    $statement = "
    SELECT MovieID FROM $table
    WHERE GenreID IN ($placeholders)
    GROUP BY MovieID
    HAVING COUNT(DISTINCT GenreID) = ?
    LIMIT ?
    ";

    $stmt = $db->prepare($statement);

    // Bind each value to its placeholder (parameters are 1-indexed in SQLite3)
    $i = 1;
    foreach ($values as $value) {
        $stmt->bindValue($i, $value, SQLITE3_INTEGER);
        $i++;
    }
    // Bind the number of values for the HAVING clause
    $stmt->bindValue($i, count($values), SQLITE3_INTEGER);
    $i++;
    // Bind the limit value
    $stmt->bindValue($i, $limit, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if (!$result) {
        array_push($errors, $db->lastErrorMsg());
    }
}

$data = [];

if (empty($errors)) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
//     if(isset($_POST) && isset($_POST['redirect'])){
//         $location = sanitizeRedirect($_POST['redirect']);
//         header("Location: {$location}");
//         exit;
//     }
//     else{
//         $location = $_SERVER['HTTP_REFERER'] ?? $GLOBALS['base_uri'].'/';
//         header("Location: {$location}");
//         exit;
//     }
// }
else {
    $data['errors'] = $errors;
//     echo htmlWrap('h1', "Sökning misslyckades!");
//     echo implode('<br>', $errors);
}

// Output as JSON
header('Content-Type: application/json');
echo json_encode($data);

?>
