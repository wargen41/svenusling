<details>
<summary><h2><?php echo admStr('ARTICLES_SECTION_TITLE'); ?></h2></summary>
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
