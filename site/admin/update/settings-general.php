<?php

$errors = [];

$table = "site";

$keys = getFirstColumnValuesFromTable($table);

$statement = 'UPDATE '.$table.' SET '."value=:value".' WHERE var=:key';

foreach ($keys as $key) {
    $value = sanitizeSingleLineText($_POST[$key]);

    $stmt = $db->prepare($statement);
    $stmt->bindValue(':key', $key, SQLITE3_TEXT);
    $stmt->bindValue(':value', $value, SQLITE3_TEXT);

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
    echo "Update failed!<br>";
    echo implode('<br>', $errors);
}

?>
