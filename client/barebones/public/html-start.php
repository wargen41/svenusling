<?php
echo "<!DOCTYPE html>";
echo "<html lang=\"sv\">";
echo "<head>";
echo "<meta charset=\"utf-8\">";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
//echo "<style>*{margin:0;}body{-webkit-font-smoothing:antialiased;}img,picture,video,canvas,svg{display:block;max-width:100%;}input,button,textarea,select{font:inherit;}p,h1,h2,h3,h4,h5,h6{overflow-wrap:break-word;}p{text-wrap:pretty;}h1,h2,h3,h4,h5,h6{text-wrap:balance;}:root{--main-margin:1.5rem;--main-margin-negative:calc(0rem - var(--main-margin));--background-color-default:rgb(238, 238, 238);--text-color-default:black;--background-color-alternate:#333;--text-color-alternate:white;--text-color-light:#333;--text-color-faint:#aaa;--background-sticky-heading:rgba(238, 238, 238, 0.85);--background-sticky-heading-opaque:rgba(238, 238, 238, 1);--gradient-sticky-heading:linear-gradient(0deg, var(--background-sticky-heading-opaque) 0%, var(--background-sticky-heading-opaque) 100%);--border-color-default:black}@media (prefers-color-scheme:dark){:root{--background-color-default:rgb(34, 34, 34);--text-color-default:#eef6ee;--background-color-alternate:#111;--text-color-alternate:#ddd;--text-color-light:#999;--text-color-faint:#555;--background-sticky-heading:rgba(34, 34, 34, 0.85);--background-sticky-heading-opaque:rgba(34, 34, 34, 1);--border-color-default:black}}html{scroll-behavior:smooth;box-sizing:content-box}body{margin:0 0 4rem 0;font-size:18px;text-align:right;font-family:Seravek,'Gill Sans Nova',Ubuntu,Calibri,'DejaVu Sans',source-sans-pro,sans-serif;color:var(--text-color-default);background-color:var(--background-color-default);padding:0}body *{text-align:left;}main>*+*{margin-top:2em}h1{margin-bottom:.7em}article>*:not(h1)+*{margin-top:1.2em}h1,h2,h3,h4,h5,h6{line-height:1.4;font-family:Avenir,Montserrat,Corbel,'URW Gothic',source-sans-pro,sans-serif}main>details{padding-top:.5rem;padding-bottom:1rem;padding-inline:1rem}main>details>summary{margin-top:-.5rem;margin-right:-1rem;margin-bottom:.5em;margin-left:-1.7em}details>summary{margin-left:-1em}details>details+details{margin-top:1em}details>details>*+*{margin-block:.5em}a{color:var(--text-color-default);text-decoration:1px underline solid var(--text-color-faint)}.widget{position:sticky;z-index:999;top:0;display:inline-block;margin:0 0 0 0;padding:0 0 0 0}.widget.left{float:left}.widget img{width:2.2rem;height:2.2rem}.widget .button{background:none;border:none;display:inline-block}.widget.left .button{padding:.7rem 1rem 1rem .5rem}.widget.right .button{padding:.5rem 1rem 1rem .7rem}.widget-label{background:none;display:inline-block;transform:translateY(-.7rem)}.widget-label:has(+.widget.right){padding:.82rem 0 1.25rem 0}.search-widget-form{display:inline-block;transform:translateY(-1.6rem)}.list.long a{text-decoration:none}.list.long li:hover a,.list.long li:active a{text-decoration:1px underline solid var(--text-color-faint)}.list.long li:hover a:hover,a:hover,a:active{text-decoration:1px underline solid}main{max-width:36em;margin:0 auto;padding:0 var(--main-margin)}main>h1,main>article>h1,main>article>hgroup:first-of-type{text-align:center;position:sticky;top:0;padding:.5rem var(--main-margin);margin-left:var(--main-margin-negative);margin-right:var(--main-margin-negative);background:var(--background-sticky-heading);background:var(--gradient-sticky-heading)}h1,hgroup *{text-align:center}h1{font-size:200%}main>p{text-align:left}a,button,details>summary,label[for]:not([for=\"\"]),input[type=\"submit\"],input[type=\"button\"]{cursor:pointer}details>summary>*{display:inline-block}footer{position:relative;width:auto;display:flex;align-items:center;justify-content:end;gap:1em;flex-direction:row;padding:1rem 0;margin-top:5rem;background:var(--background-color-alternate);color:var(--text-color-alternate);border-block:1px solid var(--border-color-default)}.skip-link{position:fixed;top:0;left:0;right:0;text-align:center;background-color:var(--background-color-alternate);color:var(--text-color-alternate);padding:.5rem;translate:0 -100%;transition:translate 150ms ease-in-out}.skip-link:focus{translate:0;z-index:999}img.circle{border-radius:50%;object-fit:cover}footer a{color:var(--text-color-alternate)}header{position:relative;width:100%;display:flex;align-items:center;justify-content:center;gap:3rem;flex-direction:row;background:var(--background-color-alternate);color:var(--text-color-alternate);border-bottom:1px solid var(--border-color-default)}header a{color:var(--text-color-alternate)}nav ul{list-style-type:none;margin:0;padding:1rem 0;line-height:.7;display:flex;gap:1rem;flex-direction:row;flex-wrap:wrap}nav ul>li{padding:.5rem}dt{float:left;margin-right:0.3em}dd:before{content:\"—\";margin-right:0.3em}</style>";
echo "<link rel=\"stylesheet\" href=\"barebones.css\">";
echo "</head>";
echo "<body>";

// Länk för tillgänglighet
echo "<a class=\"skip-link\" href=\"#main-content\">Skip to main content</a>";

// Sidhuvud
echo "<header id=\"header\">";
echo "<nav>";
echo "<ul>";
echo "<li><a href=\"movies.php\">Betygsatta filmer</a></li>";
echo "<li><a href=\"reviews.php\">Recensioner</a></li>";
echo "</ul>";
echo "</nav>";
echo "</header>";

// Sökmojäng
echo "<div class=\"widget left\" id=\"search-widget\">";
echo "<button popovertarget=\"search-widget-form\" id=\"search-widget-button\" class=\"button\" title=\"Sök\">";
echo "<img class=\"circle\" alt=\"Sök\" src=\"https://svenusling.jlxli.eu/assets/logo.jpg\">";
echo "</button>";
// Sökformulär
echo "<form popover id=\"search-widget-form\" action=\"search.php\" method=\"get\">";
$prefilled_value = $prefilled ?? "";
echo "<input type=\"search\" id=\"search-widget-input\" required name=\"query\" value=\"$prefilled_value\">";
echo "<input type=\"submit\" value=\"Sök\">";
echo "</form>";
echo "</div>";

// Menymojäng
echo "<label for=\"menu-widget-button\" class=\"widget-label\" id=\"menu-widget-label\">Meny</label>";
echo "<div class=\"widget right\" id=\"menu-widget\">";
echo "<button popovertarget=\"menu-main\" class=\"button\" id=\"menu-widget-button\" title=\"Meny\">";
echo "<img class=\"circle\" alt=\"Meny\" src=\"https://svenusling.jlxli.eu/assets/logo.jpg\">";
echo "</button>";
echo "</div>";

// Huvudmeny
echo "<nav popover class=\"main menu\" id=\"menu-main\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<div class=\"content\">";
echo "<form action=\"search.php\" method=\"get\">";
$prefilled_value = $prefilled ?? "";
echo "<input autofocus type=\"search\" size=\"12\" id=\"menu-search-input\" required name=\"query\" value=\"$prefilled_value\">";
echo "<input type=\"submit\" value=\"Sök\">";
echo "</form>";
echo "<ul>";
echo "<li><a href=\"movies.php\">Filmlista</a></li>";
echo "<ul>";
echo "<li><button popovertarget=\"menu-movies-type\" class=\"button\" id=\"menu-movies-type-button\">Efter typ</button></li>";
echo "<li><button popovertarget=\"menu-movies-rating\" class=\"button\" id=\"menu-movies-rating-button\">Efter betyg</button></li>";
echo "<li><button popovertarget=\"menu-movies-year\" class=\"button\" id=\"menu-movies-year-button\">Efter år</button></li>";
echo "<li><button popovertarget=\"menu-movies-genre\" class=\"button\" id=\"menu-movies-genre-button\">Efter genre</button></li>";
echo "</ul>";
echo "</li>";
echo "<li><button popovertarget=\"menu-persons\" class=\"button\" id=\"menu-persons-button\">Personlista</button>";
echo "<ul>";
echo "<li><a href=\"actors.php\">Skådespelare</a></li>";
echo "<li><a href=\"directors.php\">Regissörer</a></li>";
echo "</ul>";
echo "</li>";
echo "<li><a href=\"./\">Feed</a>";
echo "<ul>";
echo "<li><a href=\"./#latest\">Senast sedda</a></li>";
echo "<li><a href=\"./#videos\">Videorecensioner</a></li>";
echo "</ul>";
echo "</li>";
echo "</ul>";
echo "</div>";
echo "</nav>";
// Undermeny filmer efter typ
echo "<nav popover class=\"sub menu movies type\" id=\"menu-movies-type\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-movies-type\" id=\"back-menu-movies-type\"></button>";
echo "<label for=\"back-menu-movies-type\">Filmlista efter typ</label>";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<ul>";
echo "<li><a href=\"films.php\">Filmer</a></li>";
echo "<li><a href=\"series.php\">Serier</a></li>";
echo "<li><a href=\"miniseries.php\">Miniserier</a></li>";
echo "</ul>";
echo "</nav>";
// Undermeny filmer efter betyg
echo "<nav popover class=\"sub menu movies rating\" id=\"menu-movies-rating\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-movies-rating\" id=\"back-menu-movies-rating\"></button>";
echo "<label for=\"back-menu-movies-rating\">Filmlista efter betyg</label>";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<ul>";
echo "<li><a href=\"\">+++++</a></li>";
echo "<li><a href=\"\">++++</a></li>";
echo "<li><a href=\"\">+++</a></li>";
echo "<li><a href=\"\">++</a></li>";
echo "<li><a href=\"\">+</a></li>";
echo "<li><a href=\"\">-</a></li>";
echo "</ul>";
echo "</nav>";
// Undermeny filmer efter år
echo "<nav popover class=\"sub menu movies year\" id=\"menu-movies-year\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-movies-year\" id=\"back-menu-movies-year\"></button>";
echo "<label for=\"back-menu-movies-year\">Filmlista efter år</label>";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<ul class=\"content\">";
echo "<li><a href=\"\">2020-tal</a></li>";
echo "<li><a href=\"\">2010-tal</a></li>";
echo "<li><a href=\"\">2000-tal</a></li>";
echo "<li><a href=\"\">1990-tal</a></li>";
echo "<li><a href=\"\">1980-tal</a></li>";
echo "<li><a href=\"\">1970-tal</a></li>";
echo "<li><a href=\"\">1960-tal</a></li>";
echo "<li><a href=\"\">1950-tal</a></li>";
echo "<li><a href=\"\">1940-tal</a></li>";
echo "<li><a href=\"\">1930-tal</a></li>";
echo "<li><a href=\"\">1920-tal</a></li>";
echo "<li><a href=\"\">1910-tal</a></li>";
echo "<li><a href=\"\">1900-tal</a></li>";
echo "<li><a href=\"\">1890-tal</a></li>";
echo "</ul>";
echo "</nav>";
// Undermeny filmer efter genre
echo "<nav popover class=\"sub menu movies genres\" id=\"menu-movies-genre\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-movies-genre\" id=\"back-menu-movies-genre\"></button>";
echo "<label for=\"back-menu-movies-genre\">Filmlista efter genre</label>";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<div class=\"content\">";
echo "<ul class=\"genres common\">";
echo "<li><a href=\"\">action</a></li>";
echo "<li><a href=\"\">drama</a></li>";
echo "<li><a href=\"\">kriminalare</a></li>";
echo "<li><a href=\"\">komedi</a></li>";
echo "<li><a href=\"\">romantik</a></li>";
echo "<li><a href=\"\">skräck</a></li>";
echo "<li><a href=\"\">thriller</a></li>";
echo "<li><a href=\"\">äventyr</a></li>";
echo "</ul>";
echo "<ul class=\"genres uncommon\">";
echo "<li><a href=\"\">animerat</a></li>";
echo "<li><a href=\"\">biografi</a></li>";
echo "<li><a href=\"\">b-film</a></li>";
echo "<li><a href=\"\">dokumentär</a></li>";
echo "<li><a href=\"\">erotik</a></li>";
echo "<li><a href=\"\">familjefilm</a></li>";
echo "<li><a href=\"\">fantasy</a></li>";
echo "<li><a href=\"\">film noir</a></li>";
echo "<li><a href=\"\">historia</a></li>";
echo "<li><a href=\"\">kortfilm</a></li>";
echo "<li><a href=\"\">krig</a></li>";
echo "<li><a href=\"\">kung fu</a></li>";
echo "<li><a href=\"\">musik</a></li>";
echo "<li><a href=\"\">musikal</a></li>";
echo "<li><a href=\"\">mystik</a></li>";
echo "<li><a href=\"\">sci-fi</a></li>";
echo "<li><a href=\"\">splatter</a></li>";
echo "<li><a href=\"\">sport</a></li>";
echo "<li><a href=\"\">teater</a></li>";
echo "<li><a href=\"\">västern</a></li>";
echo "</ul>";
echo "</div>";
echo "</nav>";
// Undermeny personer
echo "<nav popover class=\"sub menu persons\" id=\"menu-persons\">";
echo "<div class=\"mobile-menu-navigation\">";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-persons\"></button>";
echo "<button popovertargetaction=\"hide\" popovertarget=\"menu-main\"></button>";
echo "</div>";
echo "<ul>";
echo "<li><a href=\"persons.php\">Alla VIP</a></li>";
echo "<li><button popovertarget=\"menu-persons-relations\" class=\"button\" id=\"menu-persons-relations-button\">Efter relationer</button></li>";
echo "</ul>";
echo "</nav>";

echo "<main id=\"main-content\">";
echo "<article>"
?>
