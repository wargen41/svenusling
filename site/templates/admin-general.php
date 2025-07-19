<details>
<summary><h2><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h2></summary>
<?php
// Display site variables
$table = "site";
echo simpleTextInputList($my_site, array(
    "prefix"=>$table,
    "delimiter"=>"<br>"
));
?>
</details>
