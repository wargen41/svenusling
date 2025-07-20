<?php

function keyValueString($array) {
    foreach ($array as $key => $value) {
        $result[] = sprintf('%s="%s"', $key, addslashes($value));
    }
    $string = implode(' ', $result);

    return $string;
}

function elmTextInput($props) {
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
        $html .= '<label'.$forName.'>'.$props['label'].'</label>'." ";
    }
    $html .= '<input type="text"'.$attrStr.'>';
    return $html;
}

function simpleTextInputList($arr, $options) {
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
        $html .= elmTextInput(array(
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
