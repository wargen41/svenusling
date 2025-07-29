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

<input type="submit">
</form>
