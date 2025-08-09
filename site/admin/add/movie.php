<p>Filmer inmatade här är dolda tills du aktivt valt att publicera dem.</p>

<form method="post" action="insert/">
<input type="hidden" name="form" value="movie">

<fieldset>
<legend>Ny film</legend>

<div class="input-row">
<?php
$autoFocus = false;
if(isset($_GET) && isset($_GET['mata'])){
    $autoFocus = true;
}

echo htmlInput(array(
    "label" => "Betyg",
    "attributes" => array(
        "required" => true,
        "type" => "number",
        "min" => 0,
        "max" => 5,
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
        "type" => "number",
        "min" => 1888,
        "max" => 2099,
        "size" => 4,
        "name" => "year",
        "id" => "add-movie-year"
    )
));
?>

</div>

</fieldset>

<input type="submit" value="Mata in">
</form>
