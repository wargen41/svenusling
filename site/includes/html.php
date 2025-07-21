<?php

/**
 * Returns a string of key="value" pairs from an associative array.
 * @param array<string, string> $arr
 * @return string
 */
function keyValueString(array $arr): string {
    $result = [];
    foreach ($arr as $key => $value) {
        $result[] = sprintf('%s="%s"', $key, addslashes($value));
    }
    $string = implode(' ', $result);

    return $string;
}

/**
 * Wraps inner HTML in an element with optional attributes.
 * @param string $elm
 * @param string $inner
 * @param array $attr
 * @return string
 */
function htmlWrap(string $elm, string $inner, array $attr = []): string {
    $html = "";
    $attrStr = "";

    if(!empty($attr)) {
        $attrStr = " ".keyValueString($attr);
    }

    $html .=
    '<'.$elm.$attrStr.'>'.
    $inner.
    '</'.$elm.'>';

    return $html;
}

/**
 * Builds an HTML table from an array of associative arrays (rows) and optional properties.
 *
 * Options (in array $props):
 *
 * array 'headers' (strings with headers to use)
 *
 * bool 'autoheaders' (default = true)
 *
 * int 'headercolumns' (to be implemented)
 * @param array<int, array<string>> $rows
 * @param array<mixed> $props
 * @return string
 */
function htmlTableFromAssocArrayRows(array $rows, array $props = []): string {
    $html = "";
    $headerHTML = "";
    $bodyHTML = "";
    $footerHTML = ""; // To be implemented

    // The key exists AND its value is an array
    if(isset($props['headers']) && is_array($props['headers'])) {
        $headerHTML .= '<tr>';
        foreach (array_keys($props['headers']) as $header) {
            $headerHTML .= htmlWrap('th', $header);
        }
        $headerHTML .= '</tr>';
    }
    // The key does not exist in the array OR the key exists and its value is true
    // Using Null Coalescing Operator (PHP 7.0+)
    // ?? checks if the key exists; if not, it uses true as the default
    else if(($props['autoheaders'] ?? true) === true){
        $headerHTML .= '<tr>';
        foreach (array_keys($rows[0]) as $header) {
            $headerHTML .= htmlWrap('th', $header);
        }
        $headerHTML .= '</tr>';
    }

    foreach ($rows as $row) {
        $bodyHTML .= '<tr>';
        foreach ($row as $value) {
            $bodyHTML .= htmlWrap('td', (string)$value);
        }
        $bodyHTML .= '</tr>';
    }

    if($headerHTML !== ""){
        $html .= htmlWrap('thead', $headerHTML);
    }

    $html .= htmlWrap('tbody', $bodyHTML);

    if($footerHTML !== ""){
        $html .= htmlWrap('tbody', $footerHTML);
    }

    return htmlWrap('table', $html);
}

/**
 * Builds an HTML table with text inputs from an array of associative arrays (rows) and optional properties.
 *
 * Options (in array $props):
 *
 * array 'headers' (strings with headers to use)
 *
 * bool 'autoheaders' (default = true)
 *
 * int 'headercolumns' (to be implemented)
 *
 * string 'primarykey' (default = key of the first column)
 * @param array<int, array<string>> $rows
 * @param array<mixed> $props
 * @return string
 */
function htmlTextInputTableFromAssocArrayRows(array $rows, array $props = []): string {
    $html = "";
    $headerHTML = "";
    $bodyHTML = "";
    $footerHTML = ""; // To be implemented

    $prefix = "";
    $headers = [];

    if(isset($props['prefix'])) {
        $prefix = $props['prefix'];
    }

    // The key exists AND its value is an array
    if(isset($props['headers']) && is_array($props['headers'])) {
        $headers = $props['headers'];
    }
    // The key does not exist in the array OR the key exists and its value is true
    // Using Null Coalescing Operator (PHP 7.0+)
    // ?? checks if the key exists; if not, it uses true as the default
    else if(($props['autoheaders'] ?? true) === true){
        $headers = array_keys($rows[0]);
    }
    $headerHTML .= '<tr>';
    foreach ($headers as $header) {
        $headerHTML .= htmlWrap('th', $header);
    }
    $headerHTML .= '</tr>';

    // If primarykey wasn't set, use the first column
    if(empty($props['primarykey'])){
        $props['primarykey'] = $headers[0];
    }

    $len = count($rows);
    for ($i=0; $i < $len; $i++) {
        $row = $rows[$i];
        $primarykey = $props['primarykey'];

        $bodyHTML .= '<tr>';
        foreach ($row as $key => $value) {
            $idStr = $prefix.$key;
            $nameID = $row[$primarykey];
            $nameStr = $key.'['.$nameID.']';

            $input = htmlTextInput(array(
                "attributes" => array(
                    "value" => $value,
                    "id" => $idStr,
                    "name" => $nameStr
                )
            ));
            $bodyHTML .= htmlWrap('td', $input);
        }
        $bodyHTML .= '</tr>';
    }

    if($headerHTML !== ""){
        $html .= htmlWrap('thead', $headerHTML);
    }

    $html .= htmlWrap('tbody', $bodyHTML);

    if($footerHTML !== ""){
        $html .= htmlWrap('tbody', $footerHTML);
    }

    return htmlWrap('table', $html);
}

/**
 * Builds an HTML table with key/value pairs as rows.
 * @param array<string> $arr
 * @param array<mixed> $props
 * @return string
 */
function htmlVerticalTableFromAssocArray(array $arr, array $props = []): string {
    $html = "";

    foreach ($arr as $key => $value) {
        $html .= '<tr>';
        $html .= htmlWrap('td', $key);
        $html .= htmlWrap('td', (string)$value);
        $html .= '</tr>';
    }

    return htmlWrap('table', $html);
}

/**
 * Builds an HTML table with key/value pairs as rows and the values as text input elements.
 * @param array<string> $arr
 * @param array<mixed> $props
 * @return string
 */
function htmlVerticalTextInputTableFromAssocArray(array $arr, array $props = []): string {
    $html = "";
    $prefix = "";

    if(isset($props['prefix'])) {
        $prefix = $props['prefix'];
    }

    foreach ($arr as $key => $value) {
        $idStr = $prefix.$key;
        $input = htmlTextInput(array(
            "attributes" => array(
                "value" => $value,
                "id" => $idStr,
                "name" => $key
            )
        ));
        $html .= '<tr>';
        $html .= htmlWrap('td', $key);
        $html .= htmlWrap('td', $input);
        $html .= '</tr>';
    }

    return htmlWrap('table', $html);
}

/**
 * Creates a text input with optional label and attributes.
 * @param array<mixed> $props
 * @return string
 */
function htmlTextInput(array $props = []): string {
    $html = "";
    $attrStr = "";

    if(!isset($props['attributes'])) {
        $props['attributes'] = [];
    }

    $attr = $props['attributes'];

    if(!empty($attr)) {
        $attrStr = " ".keyValueString($attr);
    }

    if(isset($props['label'])) {
        $forName = "";
        if(isset($attr['id'])){
            $forName = " ".keyValueString(array("for"=>$attr['id']));
        }
        $html .=
        '<label'.$forName.'>'.
        $props['label'].
        '</label>'." ";
    }
    $html .= '<input type="text"'.$attrStr.'>';
    return $html;
}

/**
 * Creates multiple text inputs from an associative array.
 * @param array<string> $arr
 * @param array<mixed> $props
 * @return string
 */
function htmlTextInputsFromArray(array $arr, array $props = []): string {
    $html = "";
    $prefix = "";
    $delimiter = "";

    if(isset($props['prefix'])) {
        $prefix = $props['prefix'];
    }
    if(isset($props['delimiter'])) {
        $delimiter = $props['delimiter'];
    }

    foreach ($arr as $var => $value) {
        $idStr = $prefix.$var;
        $html .= htmlTextInput(array(
            "label"=>$idStr,
            "attributes"=>array(
                "value"=>$value,
                "id"=>$idStr,
                "name"=>$var
            )
        ));
        $html .= $delimiter;
    }

    return $html;
}

?>
