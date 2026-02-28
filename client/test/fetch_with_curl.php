<?php

// Get movies list
function getMovies($baseUrl, $token = null) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            $token ? 'Authorization: Bearer ' . $token : ''
        ]
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
function getMovie($baseUrl, $movieId, $token = null) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/api/movies/' . $movieId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            $token ? 'Authorization: Bearer ' . $token : ''
        ]
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
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception("API Error: $httpCode - $response");
    }
    
    return json_decode($response, true);
}

// Usage example
try {
    $baseUrl = 'http://localhost:8000';
    
    // Get all movies
    $movies = getMovies($baseUrl);
    echo "Total movies: " . $movies['pagination']['total'] . "\n";
    
    // Get movies filtered by type and year
    $filtered = getMoviesFiltered($baseUrl, [
        'type' => 'film',
        'year' => '2023',
        'rating' => 7,
        'limit' => 5
    ]);
    echo "Filtered movies: " . count($filtered['data']) . "\n";
    
    // Get single movie
    $movie = getMovie($baseUrl, 1);
    echo "Movie: " . $movie['data']['title'] . "\n";
    echo "Genres: " . count($movie['data']['genres']) . "\n";
    echo "Cast: " . count($movie['data']['persons']['actor'] ?? []) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}