<h1><?php echo getStr('VIPS_LIST_TITLE'); ?></h1>

<ol class="list vips">

<?php
$query = implode(' ', [
    "SELECT Name",
    "FROM persons",
    "ORDER BY Name",
]);
$res = $GLOBALS['db']->query($query);

$listType = 'list';
$listStyle = 'simple';
include $GLOBALS['my_dir']."view/vips/item-styles/{$listType}-{$listStyle}.php";

?>

</ol>
