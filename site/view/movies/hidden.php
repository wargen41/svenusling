<?php
session_start();
$isLoggedIn = (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true);

require __DIR__.'/../../includes/collections/default.php';

$page_title = getStr('RATED_MOVIES_TITLE');

require $GLOBALS['my_dir'].'includes/snippets/html-start.php';
include $GLOBALS['my_dir'].'includes/templates/header.php';
include $GLOBALS['my_dir'].'includes/templates/site-widget.php';
?>

<main id="main-content">

<?php

$viewType = 'movies';

if($isLoggedIn){

    echo pgHeadingHTML($viewType, 'Dolda filmer');

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
        $query = implode(' ', [
            "SELECT MovieID, Title, Year, Rating",
            "FROM movies",
            "WHERE Hidden IS 1",
            "ORDER BY Sorting",
        ]);
        $res = $GLOBALS['db']->query($query);

        echo "<{$listTypeElm} class=\"{$listType} {$viewType}\">";
        include $listPath;
        echo "</{$listTypeElm}>";

        if(isset($resCount) && $resCount === 0){
            echo resCountZero();
        }
        
        $back = {$_SERVER['HTTP_REFERER']} ?? null;
        if(!is_null($back)){
            echo htmlWrap('a', 'Tillbaka', array(
                "href" => $back
            ));
        }
        
    }else{
        echo big404Image();
    }

}else{
    echo pgHeadingHTML($viewType, 'Lost you are???');
    echo big404Image();
}


?>

</main>

<?php

include $GLOBALS['my_dir'].'includes/templates/footer.php';

include $GLOBALS['my_dir'].'includes/snippets/admin-tools.php';
require $GLOBALS['my_dir'].'includes/snippets/html-end.php';

closeDB();

?>
