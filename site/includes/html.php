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
 * @param array<string, string> $attr
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
 * Builds an HTML table from an array of associative arrays (rows) and options.
 * @param array<int, array<string, mixed>> $rows
 * @param array<string, mixed> $props
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
 * Builds an HTML table with key/value pairs as rows.
 * @param array<string, mixed> $arr
 * @param array<string, mixed> $props
 * @return string
 */
function htmlVerticalTableFromAssocArray(array $arr, array $props = []): string {
    $html = "";

    foreach ($arr as $key => $value) {
        $html .= '<tr>';
        $html .= htmlWrap('td', (string)$key);
        $html .= htmlWrap('td', (string)$value);
        $html .= '</tr>';
    }

    return htmlWrap('table', $html);
}

/**
 * Creates a text input with optional label and attributes.
 * @param array<string, mixed> $props
 * @return string
 */
function htmlTextInput(array $props = []): string {
    $html = "";
    $attrStr = "";

    // Behövs allt detta? Jag kommenterar bort lite och testar utan
    // if(!isset($props['attributes'])) {
    //     $props['attributes'] = [];
    // }
    //
    // $attr = $props['attributes'];

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
 * @param array<string, mixed> $arr
 * @param array<string, mixed> $props
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
        $varStr = $prefix.'_'.$var;
        $html .= htmlTextInput(array(
            "label"=>$varStr,
            "attributes"=>array(
                "value"=>$value,
                "id"=>$varStr,
                "name"=>$var
            )
        ));
        $html .= $delimiter;
    }

    return $html;
}

?>
