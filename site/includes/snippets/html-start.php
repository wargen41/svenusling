<!DOCTYPE html>
<html lang="<?php echo $GLOBALS['my_language']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo pgTitle($page_title); ?></title>
    <?php /* Include Cascading Style Sheets */
    include $GLOBALS['my_dir'].'styles/css-reset.php';
    include $GLOBALS['my_dir'].'styles/css-base.php';
    ?>
</head>
<body id="top">
    <a class="skip-link" href="#main-content"><?php echo getStr('SKIP_TO_MAIN'); ?></a>
