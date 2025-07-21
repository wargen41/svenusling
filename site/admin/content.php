<details>
<summary><h2><?php echo admStr('CONTENT_CATEGORY_TITLE'); ?></h2></summary>

<details>
<summary><h3><?php echo admStr('INDEX_CONTENT_SECTION_TITLE'); ?></h3></summary>
<?php
// Display start page content selection

?>
Här ska man kunna välja innehåll på startsidan<br>

</details>

<details>
<summary><h3><?php echo admStr('ARTICLES_SECTION_TITLE'); ?></h3></summary>
<p><?php echo admStr('COUNT_ARTICLES').': '.countArticles(); ?></p>
<?php
$allArticles = getAllArticles();
foreach ($allArticles as $id => $languageVersion) {
    foreach ($languageVersion as $lang => $entry) {
        foreach ($entry as $key => $value) {
            echo "$id ($lang): $key = $value <br>";
        }
    }
}
?>
</details>

</details>
