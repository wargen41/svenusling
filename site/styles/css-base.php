<style>
:root
{
    --background-color-default: #ddd;
    --background-color-inverted: #333;
    --text-color-default: black;
    --text-color-inverted: white;
    --main-margin: 1.5rem;
    --main-margin-negative: calc(0rem - var(--main-margin));
}

@media (prefers-color-scheme: dark)
:root
{
    --background-color-default: #333;
    --background-color-inverted: #ddd;
    --text-color-default: white;
    --text-color-inverted: black;
}

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
    padding: 0;
    /* Positioning the site-widget */
    text-align: right;
}
body * {
    /* Default text alignment */
    text-align: left;
}
h1, h2, h3, h4, h5, h6 {
    line-height: 1.2;
    margin-top: .65em;
    margin-bottom: .65em;
    text-wrap: balance;
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
    margin: 0 auto; /* Centrera innehållet på skärmar större än maxbredd */
    padding: 0 var(--main-margin); /* Marginaler när skärmen är mindre än maxbredd */
}
main h1 {
    text-align: center;
    position: sticky;
    top: 0;
    padding: .5rem var(--main-margin);;
    margin-left: var(--main-margin-negative);
    margin-right: var(--main-margin-negative);
    background: var(--background-color-default);
}
main >
p {
    text-align: left;
}
a,
button,
details > summary,
label[for]:not([for=""])
{
    cursor: pointer;
}
details > summary > * {
    display: inline-block;
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
img.circle
{
    border-radius: 50%;
    object-fit: cover; /* Ensures the image fills the circle without distortion */
}
footer {
    position: relative;
    width: auto;
    display: flex;
    align-items: center;
    justify-content: end;
    gap: 1em;
    flex-direction: row;

    padding: 1rem 0;
    margin-top: 5rem;

    background: var(--background-color-inverted);
    color: var(--text-color-inverted);
}
footer a {
    color: var(--text-color-inverted);
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
    background-color: LightCoral;
    border: none;
    display: inline-block;
    padding: .7rem 1rem 1rem .5rem;
}

#site-widget-label {
    background-color: LightSeaGreen;
    padding: .88rem 0 1.25rem 0;
    display: inline-block;
    transform: translateY(-.6rem);
}
</style>
