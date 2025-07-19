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

<?php include 'admin-general.php'; ?>

<?php include 'admin-index-content.php'; ?>

<?php include 'admin-articles.php'; ?>

</main>
