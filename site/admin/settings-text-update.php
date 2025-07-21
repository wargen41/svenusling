<?php

$errors = [];

$table = "site_text";

$keys = getKeysFromTable($table);
$constantKeys = ['id', 'category'];

$langKeys = [];
foreach($keys as $key) {
    if(!in_array($key, $constantKeys)){
        array_push($langKeys, $key);
    }
}

$fieldsToSet = dbArrayToStringForBinding($langKeys);

$updatePrepared = 'UPDATE '.$table.' SET '.$fieldsToSet.' WHERE id=:id';

$primaryKeys = $_POST['id'];

foreach($primaryKeys as $id) {
    $stmt = $db->prepare($updatePrepared);

    $stmt->bindValue(':id', $id, SQLITE3_TEXT);
    foreach($langKeys as $lang) {
        $value = sanitizeSingleLineText($_POST[$lang][$id]);
        $stmt->bindValue(':'.$lang, $value, SQLITE3_TEXT);
    }

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
