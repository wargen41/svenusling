<p>Filmer inmatade här är dolda tills du aktivt valt att publicera dem.</p>

<form method="post" action="">

<fieldset>
<legend>Ny film</legend>

<?php
echo htmlTextInput(array(
    "label" => "Betyg",
    "attributes" => array(
        "required" => true,
        "size" => 1,
        "name" => "rating",
        "id" => "quick-movie-rating",
        "autofocus" => true
    )
));
?>

<?php
echo htmlTextInput(array(
    "label" => "Titel",
    "attributes" => array(
        "required" => true,
        "name" => "title",
        "id" => "quick-movie-title"
    )
));
?>

<?php
echo htmlTextInput(array(
    "label" => "År",
    "attributes" => array(
        "required" => false,
        "size" => 4,
        "name" => "year",
        "id" => "quick-movie-year"
    )
));
?>

</fieldset>

<input type="submit">
</form>
