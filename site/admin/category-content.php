<details>
<summary><h2>Innehåll</h2></summary>

<details>
<summary><h3>Startsidans innehåll</h3></summary>
<?php

?>
Här ska man kunna välja innehåll på startsidan<br>

</details>

<details>
<summary><h3>Artiklar/texter</h3></summary>
<p>Antal artiklar <?php echo countArticles(); ?></p>
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
