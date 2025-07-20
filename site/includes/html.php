<?php

function keyValueString($array) {
    foreach ($array as $key => $value) {
        $result[] = sprintf('%s="%s"', $key, addslashes($value));
    }
    $string = implode(' ', $result);

    return $string;
}

function htmlWrap($elm, $inner, $attr=[]) {
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

function htmlTableFromAssocArrayRows($rows, $options=[]) {
    $html = "";
    $headerHTML = "";
    $bodyHTML = "";
    $footerHTML = ""; // To be implemented

    // The key exists AND its value is an array
    if(isset($options['headers']) && is_array($options['headers'])) {
        // UNTESTED
        $headerHTML .= '<tr>';
        foreach (array_keys($options['headers']) as $header) {
            $headerHTML .= htmlWrap('th', $header, null);
        }
        $headerHTML .= '</tr>';
    }
    // The key does not exist in the array OR the key exists and its value is true
    // Using Null Coalescing Operator (PHP 7.0+)
    // ?? checks if the key exists; if not, it uses true as the default
    else if(($options['autoheaders'] ?? true) === true){
        $headerHTML .= '<tr>';
        foreach (array_keys($rows[0]) as $header) {
            $headerHTML .= htmlWrap('th', $header, null);
        }
        $headerHTML .= '</tr>';
    }

    foreach ($rows as $row) {
        $bodyHTML .= '<tr>';
        foreach ($row as $value) {
            $bodyHTML .= htmlWrap('td', $value, null);
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

function htmlVerticalTableFromAssocArray($arr, $options=[]) {
    $html = "";

    foreach ($arr as $key => $value) {
        $html .= '<tr>';
        $html .= htmlWrap('td', $key, null);
        $html .= htmlWrap('td', $value, null);
        $html .= '</tr>';
    }

    return htmlWrap('table', $html);
}

function htmlTextInput($props=[]) {
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

function htmlTextInputsFromArray($arr, $options=[]) {
    $html = "";
    $prefix = "";
    $delimiter = "";

    if(isset($options['prefix'])) {
        $prefix = $options['prefix'];
    }
    if(isset($options['delimiter'])) {
        $delimiter = $options['delimiter'];
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
