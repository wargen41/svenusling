<details>

<summary><h2><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h2></summary>
<form method="post" action="admin-update.php">
<?php
$formName = "general-site";
// Display site variables
$prefix = "site";
echo htmlVerticalTableFromAssocArray(getSiteVars());
echo htmlTextInputsFromArray(getSiteVars(), array(
    "prefix"=>$prefix,
    "delimiter"=>"<br>"
));
?>
<input type="hidden" name="form" value="<?php echo $formName; ?>">
<input type="submit">
</form>

<details>
<summary><h3><?php echo admStr('TEXT_SECTION_TITLE'); ?></h3></summary>
<form method="post" action="admin-update.php">
<?php
$formName = "general-text";
// Display site_text values
$prefix = "text";

$rows = getAllTexts();

if (!empty($rows)) {
    echo htmlTableFromAssocArrayRows($rows);
} else {
    echo "No data found.";
}
?>
<input type="hidden" name="form" value="<?php echo $formName; ?>">
<input type="submit">
</form>
</details>

</details>
