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
    $value = preg_replace('/[^0-9]/', '', $value);

    return $value;
}

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

// Search movies list
function searchMovies($baseUrl, $query) {
    $ch = curl_init();

    $query = rawurlencode($query);

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies?details=minimal&search='.$query,
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

// Get movies list
function getMovies($baseUrl) {
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies?details=minimal',
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

// Search persons list
function searchPersons($baseUrl, $query) {
    $ch = curl_init();

    $query = rawurlencode($query);

    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/persons?details=minimal&search='.$query,
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
        CURLOPT_URL => $baseUrl . '/api/persons?details=minimal',
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
