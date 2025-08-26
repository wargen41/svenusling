<form method="post" action="update/">
<input type="hidden" name="form" value="movie-visibility">

<?php
$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$location = $page_url;
echo htmlInput(array(
    "attributes" => array(
        "type" => "hidden",
        "name" => "redirect",
        "id" => "edit-movie-redirect",
        "value" => $location
    )
));
?>

<fieldset>
<legend>Synlighet</legend>

<?php
echo htmlInput(array(
    "attributes" => array(
        "type" => "hidden",
        "name" => "movieid",
        "id" => "edit-movie-movieid",
        "value" => $movies['MovieID'] ?? ''
    )
));
?>

<div class="input-row">

<?php
$visibilityOptions = array();
$visibilityOptions[0] = "Publicerad";
$visibilityOptions[1] = "Dold";
echo htmlSelect(array(
    "options" => $visibilityOptions,
    "selected" => $movies['Hidden'] ?? '',
    "attributes" => array(
        "required" => true,
        "name" => "hidden",
        "id" => "edit-movie-visibility",
    )
));

echo htmlInput(array(
    "attributes" => array(
        "type" => "submit",
        "value" => "Spara"
    )
));
?>

</div>

</fieldset>

</form>
