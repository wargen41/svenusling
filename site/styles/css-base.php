<style>
:root
{
    --main-margin: 1.5rem;
    --main-margin-negative: calc(0rem - var(--main-margin));

    --background-color-default: rgb(238, 246, 238);
    --text-color-default: black;
    --background-color-alternate: #333;
    --text-color-alternate: white;
    --text-color-light: #333;
    --text-color-faint: #aaa;

    --background-sticky-heading: rgba(238, 246, 238, 0.85);
    --background-sticky-heading-opaque: rgba(238, 246, 238, 1);
    --gradient-sticky-heading: linear-gradient(0deg, var(--background-sticky-heading-opaque) 0%, var(--background-sticky-heading-opaque) 100%);

    --border-color-default: black;
}

@media (prefers-color-scheme: dark){
:root
{
    --background-color-default: rgb(34, 34, 34);
    --text-color-default: #eef6ee;
    --background-color-alternate: #111;
    --text-color-alternate: #ddd;
    --text-color-light: #999;
    --text-color-faint: #555;

    --background-sticky-heading: rgba(34, 34, 34, 0.85);
    --background-sticky-heading-opaque: rgba(34, 34, 34, 1);
    --border-color-default: black;
}}

html
{
    scroll-behavior: smooth;
    border-box: content-box;
}
body {
    margin: 0 0 4rem 0;
    line-height: 1.6;
    font-size: 18px;
    /* https://github.com/system-fonts/modern-font-stacks#humanist */
    font-family: Seravek, 'Gill Sans Nova', Ubuntu, Calibri, 'DejaVu Sans', source-sans-pro, sans-serif;
    color: var(--text-color-default);
    background-color: var(--background-color-default);
    padding: 0;
    /* Positioning the site-widget */
    text-align: right;
}

body *
{
    /* Default text alignment */
    text-align: left;
}

/* Generell marginal mellan element i main */
main > * + * {
    margin-top: 2em;
}

/* Marginal på h1 som följs av ett syskonelement */
h1/*:has(+ *)*/
{
    margin-bottom: .7em;
}

/* Generell marginal mellan element i en artikel */
/* Ingen marginal efter h1, för där ska h1 själv styra marginalen */
article > *:not(h1) + *
{
    margin-top: 1.2em;
}

/* Generell marginal mellan element i en section */
/* En section bör inte ha någon h1, eftersom det per definition inte är huvudinnehållet */
section > * + *
{
    margin-top: .65em;
}

main > section
{
    padding-top: .5rem;
    padding-bottom: 1rem;
    padding-inline: 1rem;
    border: 1px solid var(--border-color-default);
}

h1, h2, h3, h4, h5, h6 {
    /*line-height: 1.2;*/
    /*margin-top: .65em;
    margin-bottom: .65em;*/
    /* https://github.com/system-fonts/modern-font-stacks#geometric-humanist */
    font-family: Avenir, Montserrat, Corbel, 'URW Gothic', source-sans-pro, sans-serif;
    /* https://github.com/system-fonts/modern-font-stacks#didone */
    /*font-family: Didot, 'Bodoni MT', 'Noto Serif Display', 'URW Palladio L', P052, Sylfaen, serif;*/
    /* https://github.com/system-fonts/modern-font-stacks#antique */
    /*font-family: Superclarendon, 'Bookman Old Style', 'URW Bookman', 'URW Bookman L', 'Georgia Pro', Georgia, serif;*/
}

label
{
    white-space: nowrap;
}

fieldset > *:not(legend) + *
{
    margin-top: .65em;
}

fieldset
{
    padding-top: .5rem;
    padding-bottom: 1rem;
    padding-inline: 1rem;
}

fieldset + fieldset,
fieldset + input
{
    margin-top: .8em;
}

input[type="submit"]
{
    text-align: center;
    min-width: 50%;
}

.input-row + .input-row {
    margin-top: .5em;
}

.input-row {
    display: flex;
    gap: .5em;
    flex-wrap: wrap;
    justify-content: end;
}

/* Allow the label to grow and shrink */
.input-row label {
    flex: auto;
    display: flex;
    align-items: center;
    gap: 0.3em;
}

/* But where the input has a [size] attribute, do not use flexible size */
.input-row label:has(input[size]) {
    flex: initial;
}

/* All inputs expand to fill their label */
.input-row input {
    width: 100%;
    box-sizing: border-box;
}

/* But inputs with a [size] attribute keep their natural size */
.input-row input[size] {
    width: auto;
    min-width: 0;
    flex: none;
}

main.login
{
    height: 100dvh;
    display: flex;
    align-items: center;
}

main.login > form
{
    margin: auto;
}

a
{
    color: var(--text-color-default);
    text-decoration: 1px underline solid var(--text-color-faint);
}

.list a
{
    text-decoration: none;
}

.list li:hover a,
.list li:active a
{
    text-decoration: 1px underline solid var(--text-color-faint);
}

.list li:hover a:hover,
a:hover,
a:active
{
    text-decoration: 1px underline solid;
}

/* Får inte visited att funka :( */
a:visited:hover,
a:visited:active
{
    text-decoration: 1px overline dotted;
}

a:hover:visited,
a:active:visited
{
    text-decoration: 1px overline dotted;
}

main
{
    max-width: 36em;
    margin: 0 auto; /* Centrera innehållet på skärmar större än maxbredd */
    padding: 0 var(--main-margin); /* Marginaler när skärmen är mindre än maxbredd */
}

main > h1,
main > article > h1,
main > article > hgroup:first-of-type
{
    text-align: center;
    position: sticky;
    top: 0;
    padding: .5rem var(--main-margin);;
    margin-left: var(--main-margin-negative);
    margin-right: var(--main-margin-negative);

    background: var(--background-sticky-heading);
    /* Detta funkar inte */
    background: var(--gradient-sticky-heading);
}

h1,
hgroup *
{
    text-align: center;
}

h1
{
    font-size: 200%;
}

main > p
{
    text-align: left;
}

a,
button,
details > summary,
label[for]:not([for=""]),
input[type="submit"],
input[type="button"]
{
    cursor: pointer;
}

details > summary > *
{
    display: inline-block;
}

.skip-link
{
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    text-align: center;
    background-color: var(--background-color-alternate);
    color: var(--text-color-alternate);
    padding: .5rem;
    translate: 0 -100%;
    transition: translate 150ms ease-in-out;
}

.skip-link:focus
{
    translate: 0;
    z-index: 999;
}

img.circle
{
    border-radius: 50%;
    object-fit: cover; /* Ensures the image fills the circle without distortion */
}

img.lost
{
    width: 100%;
}

footer
{
    position: relative;
    width: auto;
    display: flex;
    align-items: center;
    justify-content: end;
    gap: 1em;
    flex-direction: row;

    padding: 1rem 0;
    margin-top: 5rem;

    background: var(--background-color-alternate);
    color: var(--text-color-alternate);

    border-block: 1px solid var(--border-color-default);
}

footer a
{
    color: var(--text-color-alternate);
}

header
{
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3rem;
    flex-direction: row;

    background: var(--background-color-alternate);
    color: var(--text-color-alternate);

    border-bottom: 1px solid var(--border-color-default);
}
header a {
    color: var(--text-color-alternate);
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
    z-index: 999;
    top: 0;
    display: inline-block;
    margin: 0 0 0 0;
    padding: 0 0 0 0;
}
/* Test med input[type=button] (funkade inte, testar button istället) */
/*#site-widget input:first-of-type
{
width: 2.2rem;
height: 2.2rem;
}
#site-widget input:first-of-type
{
display: inline-block;
padding: .7rem 1rem 1rem .5rem;
}*/
/* Backup-CSS som funkar med a-element innan byte till button */
/*#site-widget img:first-of-type,
#site-widget a:first-of-type
{
    width: 2.2rem;
    height: 2.2rem;
}
#site-widget a:first-of-type
{
    display: inline-block;
    padding: .7rem 1rem 1rem .5rem;
}*/
/* Försök med button istället */
/*#site-widget img:first-of-type,
#site-widget button:first-of-type
{
    width: 2.2rem;
    height: 2.2rem;
}
#site-widget button:first-of-type
{
    display: inline-block;
    background-color: #04AA6D;
    border: none;
    padding: .7rem 1rem 1rem .5rem;
}*/
#site-widget img
{
    width: 2.2rem;
    height: 2.2rem;
}
#site-widget button
{
    /*background-color: LightCoral;*/
    background: none;
    border: none;
    display: inline-block;
    padding: .7rem 1rem 1rem .5rem;
}

#site-widget-label {
    /*background-color: LightSeaGreen;*/
    background: none;
    padding: .82rem 0 1.25rem 0;
    display: inline-block;
    transform: translateY(-.7rem);
}
</style>
