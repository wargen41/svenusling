<details>
<summary><h2><?php echo admStr('SETTINGS_CATEGORY_TITLE'); ?></h2></summary>

<details>
<summary><h3><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h3></summary>
<form method="post" action="admin-update.php">
<?php
$formName = "general-site";
$prefix = "site_";

echo htmlVerticalTextInputTableFromAssocArray(
    getSiteVars(), array(
        "prefix" => $prefix
    )
);
?>
<input type="hidden" name="form" value="<?php echo $formName; ?>">
<input type="submit">
</form>
</details>

<details>
<summary><h3><?php echo admStr('TEXT_SECTION_TITLE'); ?></h3></summary>
<form method="post" action="admin-update.php">
<?php
$formName = "general-text";
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
<input type="hidden" name="form" value="<?php echo $formName; ?>">
<input type="submit">
</form>
</details>

</details>
