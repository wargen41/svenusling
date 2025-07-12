<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $page_title;?> | <?php echo $my_site['name'];?></title>
<style type="text/css">
:root {
    --background-color-default: white;
    --background-color-inverted: #333;
    --text-color-default: #444;
    --text-color-inverted: white;
}
body {
    margin: 0 0 4rem 0;
    line-height: 1.6;
    font-size: 18px;
    /* https://github.com/system-fonts/modern-font-stacks#humanist */
    font-family: Seravek, 'Gill Sans Nova', Ubuntu, Calibri, 'DejaVu Sans', source-sans-pro, sans-serif;
    color: var(--text-color-default);
    padding: 0;
}
h1, h2, h3, h4, h5, h6 {
    line-height: 1.2;
    padding: .5rem;
    /* https://github.com/system-fonts/modern-font-stacks#geometric-humanist */
    font-family: Avenir, Montserrat, Corbel, 'URW Gothic', source-sans-pro, sans-serif;
    /* https://github.com/system-fonts/modern-font-stacks#didone */
    /*font-family: Didot, 'Bodoni MT', 'Noto Serif Display', 'URW Palladio L', P052, Sylfaen, serif;*/
    /* https://github.com/system-fonts/modern-font-stacks#antique */
    /*font-family: Superclarendon, 'Bookman Old Style', 'URW Bookman', 'URW Bookman L', 'Georgia Pro', Georgia, serif;*/
}
h1 {
    font-size: 200%;
}
main {
    max-width: 36em;
    margin: 1rem auto;
    padding: 0 1.5rem;
}
article > h1 {
    text-align: center;
    position: sticky;
    top: 0;
    background: var(--background-color-default);
}
p {
    text-align: justify;
}
.skip-link {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    text-align: center;
    background-color: var(--background-color-inverted);
    color: var(--text-color-inverted);
    padding: .5rem;
    translate: 0 -100%;
    transition: translate 150ms ease-in-out;
}
.skip-link:focus {
    translate: 0;
    z-index: 999;
}
header {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3rem;
    flex-direction: row;

    background: var(--background-color-inverted);
    color: var(--text-color-inverted);
}
header a {
    color: var(--text-color-inverted);
}
nav ul {
    list-style-type: none;
    margin: 0;
    padding: 1rem 0;
    line-height: 0.7;
    display: flex;
    gap: 1rem;
    flex-direction: row;
    flex-wrap: wrap;
}
nav ul > li {
    padding: .5rem;
}
#site-widget {
    position: sticky;
    top: 0;
    display: inline-block;
    margin: 0 0 0 0;
    padding: 0 0 0 0;
}
#site-widget img:first-of-type,
#site-widget a:first-of-type {
    width: 2.2rem;
    height: 2.2rem;
}
img.circle {
    border-radius: 50%;
    object-fit: cover; /* Ensures the image fills the circle without distortion */
}
#site-widget a:first-of-type {
    display: inline-block;
    padding: .7rem .5rem 1rem 1rem;
}
#site-name {
    height: 2.2rem;
    display: inline-block;
    transform: translateY(-30%)
}
</style>
</head>
<body>
<a class="skip-link" href="#main-content"><?php echo getStr('SKIP_TO_MAIN'); ?></a>
<?php
if(isset($_SESSION["favcolor"])) {
    include 'admin-widget.php';
}
?>
