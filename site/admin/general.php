<details>
<summary><h2><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h2></summary>
<form method="post" action="admin-update.php">
<?php
// Display site variables
$prefix = "site";
echo simpleTextInputList(getSiteVars(), array(
    "prefix"=>$prefix,
    "delimiter"=>"<br>"
));
?>
<input type="hidden" name="form" value="general">
<input type="submit">
</form>
</details>
