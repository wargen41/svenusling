<?php

function addQueryToURL(string $url, string $key, string|int $value, bool|null $replace = null): string {
    // If the replace option is set, remove the query in case it exists
    if(isset($replace)){
        $url = removeQueryFromURL($url, $key);
    }

    $query = [];
    $queryStr = "";
    // Make sure the query string contains $key,
    // without removing any other existing querys
    $url = parse_url($url);
    if(isset($url['query'])){
        parse_str($url['query'], $query);
    }
    if(!isset($query[$key])){
        $query[$key] = $value;
    }
    $queryStr = http_build_query($query);

    $queryURL = $url['scheme'].'://'.$url['host'].$url['path'].'?'.$queryStr;
    return $queryURL;
}

function removeQueryFromURL(string $url, string $key): string {
    $query = [];
    $queryStr = "";

    // Remove given key if it exists in query
    $url = parse_url($url);
    if(isset($url['query'])){
        parse_str($url['query'], $query);
        if(isset($query[$key])){
            unset($query[$key]);
        }
    }
    $queryStr = http_build_query($query);

    $queryURL = $url['scheme'].'://'.$url['host'].$url['path'].'?'.$queryStr;
    return $queryURL;
}

function removeAllQueriesFromURL(string $url): string {
    $url = parse_url($url);

    $URL = $url['scheme'].'://'.$url['host'].$url['path'];
    return $URL;
}

function sanitizeQuery(string $value): string {
    // Remove everything but lower case letters a to z and integers
    $value = preg_replace('/[^a-z0-9]/', '', $value);

    return $value;
}

function sanitizeRedirect(string $value): string {
    // Don't yet know what to do here

    return $value;
}

function splitIntoArraysByPropertyValue(array $original, string|int $key): array {
    $new = array();
    foreach($original as $item) {
        $value = $item[$key];
        if(!isset($new[$value])) {
            $new[$value] = array();
        }
        array_push($new[$value], $item);
    }
    return $new;
}

function pgTitle(string $text): string {
    return "{$text} | {$GLOBALS['my_site']['name']}";
}

/**
 * Use this for a uniform page heading.
 *
 * Different things may happen depending on the $viewType argument
 * @param string $viewType
 * @param string $text
 * @param ?string $text2
 * @return string
 */
function pgHeadingHTML(string $viewType, string $text, ?string $text2 = ""): string {
    $str = $text;
    // switch($viewType) {
    //     case "movies":
    //         $str = "Ms {$str}";
    //         break;
    //     case "movie":
    //         $str = "M {$str}";
    //         break;
    //     case "persons":
    //         $str = "Ps {$str}";
    //         break;
    //     case "person":
    //         $str = "P {$str}";
    //         break;
    //     default:
    // }

    $headingHTML = htmlWrap('h1', $str);
    if($text2){
        $headingHTML = htmlWrap('hgroup', $headingHTML.htmlWrap('p', $text2));
    }
    return $headingHTML;
}

function autoSortingString(string $title): string {
    // Här ska vi göra lite fixar sen, såsom att ta bort The från början av titeln etc.
    return $title;
}

/**
 * Converts an integer to a string of plus signs.
 *
 * If the number is 0 it returns a minus sign instead
 * @param int $arr
 * @return string
 */
function suRating(int $rating): string {
    if($rating == 0){
        return '-';
    }

    return str_pad('', $rating, '+');
}

function typesOfMovie(): array {
    return array(
        "film",
        "series",
        "season",
        "episode",
        "miniseries",
        "filmseries"
    );
}

function typesOfMovieTexts(): array {
    return array(
        "film" => "Film",
        "series" => "Serie",
        "season" => "Säsong",
        "episode" => "Avsnitt",
        "miniseries" => "Miniserie",
        "filmseries" => "Filmserie"
    );
}

function typeUndefined(string|null $type): bool {
    if($type == '' || $type == null) {
        return true;
    }
    return false;
}

function typeSeriesOrUndefined(string|null $type): bool {
    if($type == '' || $type == 'series') {
        return true;
    }
    return false;
}

function typeFilmOrUndefined(string|null $type): bool {
    if($type == '' || $type == 'film') {
        return true;
    }
    return false;
}

function typeCanBePartOfSeries(string|null $type): bool {
    if($type == 'film' || $type == 'season' || $type == 'episode') {
        return true;
    }
    return false;
}

function typeCanBePartOfSeason(string|null $type): bool {
    if($type == 'episode') {
        return true;
    }
    return false;
}

// Not done yet
function resCountZero() {
    return '<p>Hittade inget</p>';
}

function print_rPRE($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

function formActionsHTML(array $actions = ['save', 'cancel']): string {
    $actionsHTML = "";

    $actionsHTML .= '<div class="form-actions">';

    foreach($actions as $action) {
        switch($action) {
            case "save":
                $actionsHTML .= formActionsSaveHTML();
                break;
            case "cancel":
                $actionsHTML .= formActionsCancelHTML();
                break;
            case "back":
                $actionsHTML .= formActionsBackHTML();
                break;
            case "done":
                $actionsHTML .= formActionsDoneHTML();
                break;
        }
    }

    // if(in_array('save', $actions)){
    //     $actionsHTML .= formActionsSaveHTML();
    // }
    //
    // if(in_array('cancel', $actions)){
    //     $actionsHTML .= formActionsCancelHTML();
    // }

    $actionsHTML .= '</div>';

    return $actionsHTML;
}

function formActionsSaveHTML(string $text = 'Spara'): string {
    return '<input type="submit" value="'.$text.'">';
}

function formActionsCancelHTML(string $text = 'Avbryt'): string {
    $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $back_url = removeQueryFromURL($page_url, 'section');
    return htmlWrap('a', $text, array(
        "href" => $back_url,
        "class" => "button"
    ));
}
function formActionsBackHTML(): string {
    return formActionsCancelHTML('Tillbaka');
}

function formActionsDoneHTML(string $text = 'Klar'): string {
    $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $back_url = removeAllQueriesFromURL($page_url);
    return htmlWrap('a', htmlWrap('button', $text), array(
        "href" => $back_url,
        "class" => "button"
    ));
}

function editMoreLinkHTML(string $title, string $sectionID, string $linkText, string $infoText): string {
    $linkHTML = "";

    $linkHTML .= htmlWrap('legend', $title);

    //$linkHTML .= '<div class="input-row">';

    $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $section_url = addQueryToURL($page_url, 'section', $sectionID, true);
    $section_text = htmlWrap('span', $infoText);
    $linkHTML .= htmlWrap('ul', htmlWrap('li', htmlWrap('a', htmlWrap('button', $linkText), array(
        "href" => $section_url
    )).$section_text));

    //$linkHTML .= "</div>";

    $linkHTML = htmlWrap('fieldset', $linkHTML);

    return $linkHTML;
}

?>
