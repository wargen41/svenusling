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
    "GENERAL_SECTION_TITLE"=>"Allmänna inställningar",
    "TEXT_SECTION_TITLE"=>"Texter i gränssnittet",
    "INDEX_CONTENT_SECTION_TITLE"=>"Startsidans innehåll",
    "ARTICLES_SECTION_TITLE"=>"Hantera artiklar",
    "COUNT_ARTICLES"=>"Antal artiklar"
);

function admStr( $id ) {
    $lang = "sv";//$my_site["default_language"];
    $str = $GLOBALS["admin_texts"][$lang][$id];

    return $str;
}

?>
<h1><?php echo admStr('ADMIN_PAGE_TITLE'); ?></h1>

<?php include 'general.php'; ?>

<?php /*include 'text.php';*/ ?>

<?php include 'content-index.php'; ?>

<?php include 'articles.php'; ?>

</main>
