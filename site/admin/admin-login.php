<form method="post" action="login.php">

<?php

echo htmlPasswordInput(array(
    "label" => "Lösenord",
    "attributes" => array(
        "name" => "password",
        "id" => "password"
    )
));

?>

<input type="submit">
</form>
