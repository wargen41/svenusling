<?php
require __DIR__ . '/vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
$md_converter = new CommonMarkConverter([
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]);

require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

if(isset($_GET) && isset($_GET['query'])){
    $query = $_GET['query'] ?? null;
}
$prefilled = $query ?? "";

require __DIR__ . '/html-start.php';
?>
