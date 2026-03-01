<?php
require __DIR__ . '/config.php';
require __DIR__ . '/api-requests.php';

require __DIR__ . '/html-start.php';

try {
    $baseUrl = API_BASE_URL;

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        // Get single award
        $award = getAward($baseUrl, $id);
        $data = $award['data'];

        $award_name = $data['award'];
        $category = $data['category'];
        if($category === ""){
            $category = "<em>Huvudkategori?</em>";
        }
        $movies = $data['movie_nominations'];
        $persons = $data['person_nominations'];

        echo "<h1>$award_name &mdash; $category</h1>";

        if(!empty($movies)){
            $summary = count($movies)." nomineringar";
            $open_str = "";
            if(count($movies) < 11){
                $open_str = " open";
            }
            echo "<h2>Nominerade filmer</h2>";
            echo "<details$open_str>";
            echo "<summary>$summary</summary>";
            echo "<ul>";
            foreach($movies as $item){
                $year = $item['year'];
                $title = $item['title'];
                $won_str = "";
                $str_start = "";
                $str_end = "";
                if($item['won'] === 1){
                    $won_str = " VINNARE";
                    $str_start = "<strong>";
                    $str_end = "</strong>";
                }
                $note = $item['note'] ?? null;
                $note_str = "";
                if(isset($note) && $note !== ""){
                    $note_str = " &ndash; $note";
                }
                echo "<li>$str_start$title ($year)$str_end$won_str$note_str</li>";
            }
            echo "</ul>";
            echo "</details>";
        }

        if(!empty($persons)){
            $summary = count($persons)." nomineringar";
            $open_str = "";
            if(count($persons) < 11){
                $open_str = " open";
            }
            echo "<h2>Nominerade personer</h2>";
            echo "<details$open_str>";
            echo "<summary>$summary</summary>";
            echo "<ul>";
            foreach($persons as $item){
                $year = $item['year'];
                $title = $item['title'] ?? null;
                $title_str = "";
                if(isset($title) && $title !== ""){
                    $title_str = " | $title";
                }
                $name = $item['person_name'];
                $won_str = "";
                $str_start = "";
                $str_end = "";
                if($item['won'] === 1){
                    $won_str = " VINNARE";
                    $str_start = "<strong>";
                    $str_end = "</strong>";
                }
                $note = $item['note'] ?? null;
                $note_str = "";
                if(isset($note) && $note !== ""){
                    $note_str = " &ndash; $note";
                }
                echo "<li>$str_start$name$title_str ($year)$str_end$won_str$note_str</li>";
            }
            echo "</ul>";
            echo "</details>";
        }

        echo print_rPRE($award['data']);

    }else{
        echo "Inget id angett";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

require __DIR__ . '/html-end.php';
