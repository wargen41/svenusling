<?php
echo "<html>";
echo "<head>";
echo "<style>body{margin:40px auto;max-width:770px;line-height:1.6;font-size:18px;color:#444;padding:0 10px}h1,h2,h3{line-height:1.2}</style>";
echo "</head>";
echo "<body>";
echo "<nav>";
echo "<a href=\"./\">Start</a>";
echo " | ";
echo "<a href=\"movies.php\">Filmlista</a>";
echo " | ";
echo "<a href=\"persons.php\">VIP-lista</a>";
echo " | ";
echo "<a href=\"awards-categories.php\">Utmärkelser</a>";
echo "</nav>";
echo "<form action=search.php method=get>";
$prefilled_value = $prefilled ?? "";
echo "<input type=\"search\" name=\"query\" value=\"$prefilled_value\">";
echo "<input type=\"submit\" value=\"Sök\">";
echo "</form>";
?>
