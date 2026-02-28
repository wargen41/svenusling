<?php

function suRating(int $rating): string {
    if($rating == 0){
        return '-';
    }

    return str_pad('', $rating, '+');
}

function print_rPRE($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

// Get movies list
function getMovies($baseUrl) {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies',
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }

    return json_decode($response, true);
}

// Get single movie with all data
function getMovie($baseUrl, $id) {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies/' . $id,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }

    return json_decode($response, true);
}

// Get movies with filters
function getMoviesFiltered($baseUrl, $filters = []) {
    $query = http_build_query($filters);
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies?' . $query,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }

    return json_decode($response, true);
}

// Get persons list
function getPersons($baseUrl) {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/persons',
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }

    return json_decode($response, true);
}

// Get single person with all data
function getPerson($baseUrl, $id) {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/persons/' . $id,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }

    return json_decode($response, true);
}
