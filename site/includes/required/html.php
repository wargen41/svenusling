<?php

/**
 * Returns a string of key="value" pairs from an associative array.
 *
 * If a value is boolean false, that key will not be included
 *
 * If a value is boolean true, only the key will be included (no value)
 * @param array<string, string> $arr
 * @return string
 */
function htmlKeyValueString(array $arr): string {
    $result = [];
    foreach ($arr as $key => $value) {
        if($value !== false){
            if($value === true){
                $result[] = $key;
            }else{
                $result[] = sprintf('%s="%s"', $key, addslashes($value));
            }
        }
    }
    $string = implode(' ', $result);

    return $string;
}

/**
 * Wraps inner HTML in an element with optional attributes.
 * @param string $elm
 * @param string $inner
 * @param array $attr
 * @param array $newline
 * @return string
 */
function htmlWrap(string $elm, string $inner, array $attr = [], array $newline = ["\n", "", "", ""]): string {
    $html = "";
    $attrStr = "";

    if(!empty($attr)) {
        $attrStr = " ".htmlKeyValueString($attr);
    }

    $html .=
    $newline[0]."<{$elm}{$attrStr}>".
    $newline[2].$inner.$newline[3].
    "</{$elm}>".$newline[1];

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
 * int 'columnheaders' (default = 0)
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

    $prefix = $props['prefix'] ?? "";
    $headers = [];
    $numberOfColumnHeaders = $props['columnheaders'] ?? 0;

    // if(isset($props['prefix'])) {
    //     $prefix = $props['prefix'];
    // }

    $headersAuto = array_keys($rows[0]);
    // The key exists AND its value is an array
    if(isset($props['headers']) && is_array($props['headers'])) {
        $headers = $props['headers'];

        // If the headers provided are not enough for all columns AND
        // autoheaders are on, fill remaining columns with automatic headers
        $hCount = count($headers);
        $aCount = count($headersAuto);
        if($hCount < $aCount){
            $headers = array_merge($headers, array_slice($headersAuto, $hCount));
        }
    }
    // The key does not exist in the array OR the key exists and its value is true
    // Using Null Coalescing Operator (PHP 7.0+)
    // ?? checks if the key exists; if not, it uses true as the default
    else if(($props['autoheaders'] ?? true) === true){
        $headers = $headersAuto;
    }
    $headerHTML .= '<tr>';
    foreach ($headers as $header) {
        $headerHTML .= htmlWrap('th', $header);
    }
    $headerHTML .= '</tr>';

    // If primarykey wasn't set, use the first column
    if(empty($props['primarykey'])){
        $props['primarykey'] = array_keys($rows[0])[0];
    }

    $len = count($rows);
    for ($i=0; $i < $len; $i++) {
        $row = $rows[$i];
        $primarykey = $props['primarykey'];

        $bodyHTML .= '<tr>';

        $columnCounter = 0;
        foreach ($row as $key => $value) {
            $columnCounter++;

            $idStr = $prefix.$key;
            $nameID = $row[$primarykey];
            $nameStr = $key.'['.$nameID.']';

            if($columnCounter > $numberOfColumnHeaders){
                $textInput = htmlInput(array(
                    "attributes" => array(
                        "value" => $value,
                        "id" => $idStr,
                        "name" => $nameStr
                    )
                ));
                $bodyHTML .= htmlWrap('td', $textInput);
            }else{
                $thContent = $value;
                if($key === $primarykey) {
                    $hiddenInput = htmlInput(array(
                        "attributes" => array(
                            "type" => "hidden",
                            "value" => $value,
                            "id" => $idStr,
                            "name" => $nameStr
                        )
                    ));
                    $thContent .= $hiddenInput;
                }
                $bodyHTML .= htmlWrap('th', $thContent);
            }
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
        $input = htmlInput(array(
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
 * Creates an input with optional label and attributes. If no attribute for type is set, the default is text.
 * @param array<mixed> $props
 * @return string
 */
function htmlInput(array $props = []): string {
    $html = "";
    $attrStr = "";

    if(!isset($props['attributes'])) {
        $props['attributes'] = array("type" => "text");
    }else{
        if(!isset($props['attributes']['type'])){
            $props['attributes']['type'] = 'text';
        }
    }

    $attr = $props['attributes'];
    $attrStr = " ".htmlKeyValueString($attr);

    $inputHTML = '<input'.$attrStr.'>';

    if(isset($props['label'])) {
        $labelAttr = array();
        if(isset($attr['id'])){
            $labelAttr = array("for" => $attr['id']);
        }
        $inputHTML = htmlWrap('label', "{$props['label']} {$inputHTML}", $labelAttr);
    }

    $html .= $inputHTML;
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
        $html .= htmlInput(array(
            "label" => $idStr,
            "attributes"=>array(
                "type" => "text",
                "value" => $value,
                "id" => $idStr,
                "name" => $var
            )
        ));
        $html .= $delimiter;
    }

    return $html;
}

function htmlSelect(array $props = []): string {
    $html = "";
    $attrStr = "";
    $optionsHTML = "";

    if(isset($props['options'])) {
        foreach ($props['options'] as $value => $text) {
            $selected = false;
            if(isset($props['selected']) && $props['selected'] === $value){
                $selected = true;
            }

            $optionsHTML .= htmlWrap('option', $text, array(
                "value" => $value,
                "selected" => $selected
            ));
        }
    }

    if(!isset($props['attributes'])) {
        $props['attributes'] = array();
    }

    $selectHTML = htmlWrap('select', $optionsHTML, $props['attributes']);

    if(isset($props['label'])) {
        $labelAttr = array();
        if(isset($attr['id'])){
            $labelAttr = array("for" => $attr['id']);
        }
        $selectHTML = htmlWrap('label', "{$props['label']} {$selectHTML}", $labelAttr);
    }

    $html .= $selectHTML;
    return $html;
}

?>
