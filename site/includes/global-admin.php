<?php

$GLOBALS["admin_texts"] = [];
$GLOBALS["admin_texts"]["sv"] = array(
    "ADMIN_PAGE_TITLE"=>"Administration",

    "QUICK_CATEGORY_TITLE"=>"Snabbstart",
    "QUICK_MOVIE_TITLE"=>"Mata in filmer",

    "DB_CATEGORY_TITLE"=>"Databas",
    "MOVIES_SECTION_TITLE"=>"Filmer",
    "PERSONS_SECTION_TITLE"=>"Personer",

    "CONTENT_CATEGORY_TITLE"=>"Innehåll",
    "INDEX_CONTENT_SECTION_TITLE"=>"Startsidans innehåll",
    "ARTICLES_SECTION_TITLE"=>"Artiklar/texter",
    "COUNT_ARTICLES"=>"Antal artiklar",

    "SETTINGS_CATEGORY_TITLE"=>"Inställningar",
    "GENERAL_SECTION_TITLE"=>"Allmänt",
    "TEXT_SECTION_TITLE"=>"Text i gränssnittet",
);

function admStr( $id ) {
    $lang = "sv";
    $str = $GLOBALS["admin_texts"][$lang][$id];

    return $str;
}

?>
