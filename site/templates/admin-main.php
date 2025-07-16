<main id="main-content">
<?php
// Set session variables
$_SESSION["favcolor"] = "green";
$_SESSION["favanimal"] = "cat";
echo "Session variables are set.";

// Admin page texts
$GLOBALS["admin_texts"] = [];
$GLOBALS["admin_texts"]["sv"] = array(
    "ADMIN_PAGE_TITLE"=>"Administrationssida",
    "GENERAL_SECTION_TITLE"=>"Generellt",
    "INDEX_CONTENT_SECTION_TITLE"=>"Startsidans innehåll",
    "ARTICLES_SECTION_TITLE"=>"Hantera artiklar"
);

function admStr( $id ) {
    $lang = "sv";//$my_site["default_language"];
    $str = $GLOBALS["admin_texts"][$lang][$id];

    return $str;
}

?>
<h1><?php echo admStr('ADMIN_PAGE_TITLE'); ?></h1>

<details>
<summary><h2><?php echo admStr('GENERAL_SECTION_TITLE'); ?></h2></summary>
<?php
// Display site variables
foreach ($my_site as $var => $value) {
    echo "$var: $value <br>";
}
?>
</details>

<details>
<summary><h2><?php echo admStr('INDEX_CONTENT_SECTION_TITLE'); ?></h2></summary>
<?php
// Display start page content selection

?>
Här ska man kunna välja innehåll på startsidan<br>

</details>

<details>
<summary><h2><?php echo admStr('ARTICLES_SECTION_TITLE'); ?></h2></summary>
<?php
// Display start page content selection
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

</main>
