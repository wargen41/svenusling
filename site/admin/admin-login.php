<main id="main-content" class="login">

<form method="post" action="login.php">

<?php

echo htmlInput(array(
    "label" => "Lösenord",
    "attributes" => array(
        "type" => "password",
        "name" => "password",
        "id" => "password"
    )
));

?>

<input class="login" type="submit" value="Logga in">
</form>

</main>
