<p>Filmer inmatade här är dolda tills du aktivt valt att publicera dem.</p>

<form method="post" action="insert/index.php">
<input type="hidden" name="form" value="movie">

<fieldset>
<legend>Ny film</legend>

<?php
$autoFocus = false;
if(isset($_GET) && isset($_GET['mata'])){
    $autoFocus = true;
}

echo htmlInput(array(
    "label" => "Betyg",
    "attributes" => array(
        "required" => true,
        "size" => 1,
        "name" => "rating",
        "id" => "add-movie-rating",
        "autofocus" => $autoFocus
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "Titel",
    "attributes" => array(
        "required" => true,
        "name" => "title",
        "id" => "add-movie-title"
    )
));
?>

<?php
echo htmlInput(array(
    "label" => "År",
    "attributes" => array(
        "required" => false,
        "size" => 4,
        "name" => "year",
        "id" => "add-movie-year"
    )
));
?>

</fieldset>

<input type="submit">
</form>
