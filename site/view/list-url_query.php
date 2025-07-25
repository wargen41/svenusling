<?php

// Default values
$listType = 'list';
$listStyle = 'simple';

// Use values from URL query if there is one
if(isset($_GET['type'])){
    $listType = sanitizeQuery($_GET['type']);
}

if(isset($_GET['style'])){
    $listType = sanitizeQuery($_GET['style']);
}

$listTypeElm = $listType; // table
if($listType == 'list'){
    $listTypeElm = 'ol';
}

$listPath = $GLOBALS['my_dir']."view/{$viewType}/item-styles/{$listType}-{$listStyle}.php";

if(file_exists($listPath)){
    echo "<{$listTypeElm} class=\"{$listType} {$viewType}\">";
    include $listPath;
    echo "</{$listTypeElm}>";
}else{
    echo big404Image();
}

?>
