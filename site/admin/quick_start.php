<details open>
<summary><h2>Snabbstart</h2></summary>

<details open>
<summary><h3>Mata in film</h3></summary>
<?php include $GLOBALS['my_dir'].'admin/add/movie.php' ?>
</details>

<details open>
<summary><h3>Dolda filmer</h3></summary>
<?php
$hiddenCount = dbCountHiddenMovies();
echo htmlWrap('p', "Det finns {$hiddenCount} som ännu inte publicerats.");
if($hiddenCount > 0) {
    echo htmlWrap('a', 'Gå till listan', array(
        "href" => $GLOBALS['base_uri'].'/view/movies/hidden.php'
    ));
}
?>
</details>

</details>
