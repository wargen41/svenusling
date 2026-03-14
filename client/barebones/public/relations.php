<?php
require __DIR__ . '/includes.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){
        // Get one relation
        $id = $_GET['id'];
        $data = [getRelation($baseUrl, $id)['data']];
    }else{
        // Get all relations
        $relations = getRelations($baseUrl);

        $data = [];
        foreach($relations['data'] as $rel){
            $id = $rel['id'];
            $data[] = getRelation($baseUrl, $id)['data'];
        }
    }

    echo mainHeading("Relationer");
    
    foreach($data as $cat){
        $cat_text = $cat['sv'];
        $pairs = $cat['person_pairs'];
        $cat_count = count($pairs);
        echo "<details open>";
        echo "<summary>$cat_text ($cat_count)</summary>";
        echo "<ul>";
        foreach ($pairs as $item) {
            $name1 = $item['person_1_name'];
            $name2 = $item['person_2_name'];
            echo "<li>$name1 &mdash; $name2</li>";
        }
        echo "</ul>";
        echo "</details>";
    }
    
    //print_rPRE($data);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
