<details>

<summary><h2><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h2></summary>
<form method="post" action="admin-update.php">
<?php
$formName = "general";
// Display site variables
$prefix = "site";
echo simpleTextInputList(getSiteVars(), array(
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

// 1. Check for data
if (!empty($rows)) {
    echo '<table>';

    // 2. Output headers
    echo '<tr>';
    foreach (array_keys($rows[0]) as $header) {
        echo "<th>{$header}</th>";
    }
    echo '</tr>';

    // 3. Output data rows
    foreach ($rows as $row) {
        echo '<tr>';
        foreach ($row as $value) {
            echo "<td>{$value}</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "No data found.";
}
?>
<input type="hidden" name="form" value="<?php echo $formName; ?>">
<input type="submit">
</form>
</details>

</details>
