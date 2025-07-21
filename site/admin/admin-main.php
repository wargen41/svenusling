<main id="main-content">
<?php

$GLOBALS["admin_texts"] = [];
$GLOBALS["admin_texts"]["sv"] = array(
    "ADMIN_PAGE_TITLE"=>"Administrationssida",
    "SETTINGS_CATEGORY_TITLE"=>"Inställningar",
    "CONTENT_CATEGORY_TITLE"=>"Innehåll",
    "GENERAL_SECTION_TITLE"=>"Allmänt",
    "TEXT_SECTION_TITLE"=>"Text i gränssnittet",
    "INDEX_CONTENT_SECTION_TITLE"=>"Startsidans innehåll",
    "ARTICLES_SECTION_TITLE"=>"Artiklar/texter",
    "COUNT_ARTICLES"=>"Antal artiklar"
);

function admStr( $id ) {
    $lang = "sv";
    $str = $GLOBALS["admin_texts"][$lang][$id];

    return $str;
}

?>
<h1><?php echo admStr('ADMIN_PAGE_TITLE'); ?></h1>

<?php include 'general.php'; ?>

<?php include 'content.php'; ?>

</main>

<?php
// Set session variables demo
$_SESSION["favcolor"] = "green";
$_SESSION["favanimal"] = "cat";
echo "Session variables are set.";
?>
