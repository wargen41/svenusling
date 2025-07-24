<?php
session_start();
require __DIR__.'/../../includes/collections/default.php';

if(isset($_GET) && isset($_GET['id'])){
    $id = sanitizeSingleLineText($_GET['id']); // Sanitize ska vara endast siffror sen

    $query = implode(' ', [
        "SELECT *",
        "FROM movies",
        "WHERE Hidden IS NOT 1 AND MovieID = ".$id,
    ]);
    $res = $GLOBALS['db']->query($query);

    $row = $res->fetchArray(SQLITE3_ASSOC);

    $snabbData = array();
    foreach($row as $key => $value){
        if($value === null){
            $value = 'NULL';
        }if(is_int($value)){
            $value = (string)$value;
        }
        $snabbData[$key] = htmlSafeOutput($value);
    }

}

$page_title = $snabbData['Title'];

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<h1><?php echo $snabbData['Title']; ?></h1>
<?php

print_rPRE($snabbData);

?>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
