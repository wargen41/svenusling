<?php
require __DIR__ . '/vendor/autoload.php';
use League\CommonMark\CommonMarkConverter;
$md_converter = new CommonMarkConverter([
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]);

require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        // Get single person
        $person = getPerson($baseUrl, $id);
        $data = $person['data'];

        $name = $data['name'];
        $category = $data['category'];

        $birth_date = $data['birth_date'] ?? null;
        $death_date = $data['death_date'] ?? null;
        $dates = [];
        if($birth_date){
            array_push($dates, $birth_date);
        }
        if($death_date){
            array_push($dates, $death_date);
        }

        $filmography = $data['filmography'];
        $relations = $data['relations'];
        $trivia = $data['trivia'];

        echo "<h1>$name ($category)</h1>";
        if(!empty($dates)){
            $dates_str = implode('&ndash;', $dates);
            echo "<p>$dates_str</p>";
        }
        if(!empty($filmography)){
            echo "<h2>Medverkan</h2>";
            echo "<ul>";
            foreach($filmography as $item){
                $id = $item['id'];
                $title = $item['title'];
                $year = $item['year'] ?? null;
                $year_str = '';
                if($year){
                    $year_str = " ($year)";
                }
                echo "<li><a href=\"movie.php?id=$id\">$title$year_str</a></li>";
            }
            echo "</ul>";
        }
        if(!empty($relations)){
            echo "<h2>Relationer</h2>";
            echo "<ul>";
            foreach($relations as $item){
                $text = $item['sv'];
                $text_str = " ($text)";
                $rel_name = $item['person_2_name'];
                echo "<li>$rel_name$text_str</li>";
            }
            echo "</ul>";
        }
        if(!empty($trivia)){
            echo "<h2>Visste du?</h2>";
            echo "<ul>";
            foreach($trivia as $item){
                $md = "";
                if($item['en']){
                    $md = $item['en'];
                }else{
                    $md = $item['sv'];
                }
                $html = $md_converter->convert($md);
                echo "<li>$html</li>";
            }
            echo "</ul>";
        }

        echo print_rPRE($person['data']);

    }else{
        echo "Inget id angett";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
