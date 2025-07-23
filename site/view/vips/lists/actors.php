<h1><?php echo getStr('ACTORS_LIST_TITLE'); ?></h1>

<ol class="list vips actors">

<?php
$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "WHERE Category = 'actor' OR Category = 'voice_actor'",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

$listType = 'list';
$listStyle = 'simple';
include $GLOBALS['my_dir']."view/vips/item-styles/{$listType}-{$listStyle}.php";

?>

</ol>
