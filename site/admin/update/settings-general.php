<?php

$errors = [];

$table = "site";

$keys = getFirstColumnValuesFromTable($table);

$updatePrepared = 'UPDATE '.$table.' SET '."value=:value".' WHERE var=:key';

foreach ($keys as $key) {
    $value = sanitizeSingleLineText($_POST[$key]);

    $stmt = $db->prepare($updatePrepared);
    $stmt->bindValue(':key', $key, SQLITE3_TEXT);
    $stmt->bindValue(':value', $value, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        array_push($errors, $db->lastErrorMsg());
    }
}

if (empty($errors)) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
else {
    echo "Update failed!<br>";
    echo implode('<br>', $errors);
}

?>
