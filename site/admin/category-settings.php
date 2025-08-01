<details>
<summary><h2>Inställningar</h2></summary>

<details>
<summary><h3>Allmänt</h3></summary>
<form method="post" action="update/index.php">
<input type="hidden" name="form" value="settings-general">
<?php
$prefix = "site_";

echo htmlVerticalTextInputTableFromAssocArray(
    getSiteVars(), array(
        "prefix" => $prefix
    )
);
?>
<input type="submit">
</form>
</details>

<details>
<summary><h3>Text i gränssnittet</h3></summary>
<form method="post" action="update/index.php">
<input type="hidden" name="form" value="settings-text">
<?php
$formName = "settings-text";
$prefix = "text_";

$rows = getAllTexts();

if (!empty($rows)) {
    //echo htmlTableFromAssocArrayRows($rows);
    echo htmlTextInputTableFromAssocArrayRows($rows, array(
        "headers" => array(
            "Textens namn", "Kategori"
        ),
        "autoheaders" => true,
        "columnheaders" => 2
    ));
} else {
    echo "No data found.";
}
?>
<input type="submit">
</form>
</details>

</details>
