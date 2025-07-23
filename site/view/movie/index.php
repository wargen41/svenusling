<?php
session_start();
require __DIR__.'/../../includes/collections/default.php';

$query = implode(' ', [
    "SELECT Title, Year, Rating",
    "FROM movies",
    "WHERE Hidden IS NOT 1 AND MovieID = 10002",
]);
$res = $GLOBALS['db']->query($query);

$row = $res->fetchArray(SQLITE3_ASSOC);

$title = htmlSafeOutput($row['Title']);
$year = htmlSafeOutput($row['Year']);

$page_title = $title;

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<p>Här ska vara en sida som visar alla möjliga uppgifter om en enskild film eller serie.</p>

<h1><?php echo $title; ?></h1>

<p><?php echo $year ?></p>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
