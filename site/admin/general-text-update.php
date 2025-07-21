<?php
    $table = "site_text";

    // Work in progress
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

        if ($result) {
            echo "$id updated successfully!";
        } else {
            echo "Update failed: ".$db->lastErrorMsg();
        }
        echo "<br>";
    }

?>
